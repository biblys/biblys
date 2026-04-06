# Refactoring : Extraction de la logique de paiement en Use Cases

## Contexte

La logique de paiement vit dans `OrderManager` (`inc/Order.class.php`), une
classe legacy qui mélange persistance et logique métier. Deux méthodes sont
concernées :

- **`addPayment()`** (l.507-558) : enregistre un paiement, met à jour les
  montants, et si tout est payé, crée le container DI inline pour appeler
  `markAsPayed()`
- **`markAsPayed()`** (l.564-669) : date de paiement, transfert ebooks,
  auto-expédition si pas de physique, email de confirmation en HTML dur

**Objectif** : migrer vers des usecases Propel, chaque étape mergeable
indépendamment.

---

## Phase 0 — Test de non-régression au niveau contrôleur

Il n'existe aucun test contrôleur pour les flux de paiement. Seul le marquage admin est testable facilement en l'état (les webhooks Stripe/PayPlug/PayPal instancient `OrderManager`/`PaymentManager` avec `new` et appellent des API statiques externes, ce qui les rend impossibles à tester sans refacto préalable).

### 0.1 — Ajouter `testUpdateActionToMarkOrderAsPayed`

**Fichier** : `tests/AppBundle/Controller/OrderControllerTest.php`

Suivre le pattern exact de `testUpdateActionToMarkOrderAsShipped` (l.128) :

```php
public function testUpdateActionToMarkOrderAsPayed()
{
    // given
    $controller = new OrderController();
    $order = ModelFactory::createOrder();
    $payload = json_encode(["payment_mode" => "cash", "tracking_number" => null]);
    $request = new Request(content: $payload);
    $currentSite = Mockery::mock(CurrentSite::class);
    $currentUser = Mockery::mock(CurrentUser::class);
    $currentUser->shouldReceive("authAdmin")->once();
    $templateService = Mockery::mock(TemplateService::class);
    $mailer = Mockery::mock(Mailer::class);
    $mailer->shouldReceive("send")->once();

    // when
    $response = $controller->updateAction(
        $request, $currentSite, $currentUser,
        $templateService, $mailer,
        $order->getId(), "payed"
    );

    // then
    $this->assertEquals(200, $response->getStatusCode());
    $updatedOrder = OrderQuery::create()->findPk($order->getId());
    $this->assertNotNull($updatedOrder->getPaymentDate());
}
```

**Attention** : `updateAction` utilise `new OrderManager()` en interne. Il faut s'assurer que l'entité legacy correspondante existe aussi (vérifier si `ModelFactory::createOrder()` crée à la fois le Propel Order et l'entité legacy, ou s'il faut aussi utiliser `EntityFactory`).

### Filet de sécurité pendant la transition

1. **Test 0.1** pour le flux admin
2. **Tests unitaires des usecases** (phases B et D) pour la logique métier
3. **Tests legacy existants** (`OrderTest`, `OrderManagerTest`) qui continuent de passer
4. Les webhooks deviendront testables après le refacto (contrôleur mince + usecase injecté)

---

## Phase A — Préparation (nouveaux fichiers uniquement, zéro changement existant)

### A.1 — Ajouter `containsPhysicalArticles()` et
`containsDownloadableArticles()` sur `\Model\Order`

`Cart` a déjà `containsDownloadableArticles()` et `containsPhysicalArticles()` (
`src/Model/Cart.php:75-86`). Order a besoin des mêmes méthodes pour le usecase.

**Fichier** : `src/Model/Order.php`

- Ajouter `containsDownloadableArticles(): bool` — itérer `getStockItems()`,
  vérifier `$item->getArticle()->getType()->isDownloadable()`
- Ajouter `containsPhysicalArticles(): bool` — itérer `getStockItems()`,
  vérifier `$item->getArticle()->getType()->isPhysical()`
- Ajouter les tests correspondants

**Fichiers modifiés** : `src/Model/Order.php`  
**Fichiers créés** : aucun (tests dans un fichier existant ou nouveau
`tests/Model/OrderTest.php`)

---

### A.2 — Créer le template Twig `order-paid-email.html.twig`

S'inspirer de
`src/AppBundle/Resources/views/Order/order-shipped-email.html.twig` qui étend
`layout:email-layout.html.twig`.

**Fichier** : `src/AppBundle/Resources/views/Order/order-paid-email.html.twig`

Variables du template :

- `subject` — sujet de l'email
- `order_id` — numéro de commande
- `order_total` — montant formaté
- `payment_mode` — mode de paiement
- `order_url` — lien vers le suivi
- `has_downloadable` — boolean, affiche le lien bibliothèque numérique
- `library_url` — URL de la bibliothèque numérique

---

## Phase B — Créer `MarkOrderAsPayedUsecase`

### B.1 — Créer le usecase

**Fichier** : `src/Usecase/MarkOrderAsPayedUsecase.php`

```php
class MarkOrderAsPayedUsecase
{
    public function __construct(
        private readonly CurrentSite $currentSite,
        private readonly UrlGenerator $urlGenerator,
        private readonly TemplateService $templateService,
        private readonly Mailer $mailer,
    ) {}

    public function execute(\Model\Order $order): void
    {
        // 1. Enregistrer la date de paiement
        $order->setPaymentDate(new DateTime());

        // 2. Boucler sur les stock items
        //    - Séparer ebooks / physiques (via ArticleType::isDownloadable / isPhysical)
        //    - Assigner les physiques au user : $item->setUser($order->getUser()); $item->save()

        // 3. Transférer les ebooks dans la bibliothèque
        //    - Filtrer les items dont getArticle()->getType()->isDownloadable()
        //    - Appeler AddArticleToUserLibraryUsecase (déjà existant, src/Usecase/AddArticleToUserLibraryUsecase.php)
        //    - Passer sendEmail: true

        // 4. Si pas de produits physiques → $order->setShippingDate(new DateTime())

        // 5. $order->save()

        // 6. Envoyer l'email de confirmation via TemplateService + Mailer
        //    - Rendre le template order-paid-email.html.twig
        //    - Envoyer via $this->mailer->send()
    }
}
```

**Points d'attention** :

- Le usecase utilise `\Model\Order` (Propel), pas l'entité legacy
- `AddArticleToUserLibraryUsecase` est instancié avec `$this->mailer` et appelé
  avec `$this->currentSite`, `$this->urlGenerator`
- Le code legacy assigne `$stock->set('user_id', $order->get('user_id'))` pour
  les items physiques → en Propel : `$item->setUser($order->getUser())`
- L'email legacy catch les exceptions silencieusement → garder ce comportement

### B.2 — Créer les tests

**Fichier** : `tests/Usecase/MarkOrderAsPayedUsecaseTest.php`

Cas de test :

1. **Commande simple** (pas d'ebooks, pas de physique) — vérifie `paymentDate`
   définie, `shippingDate` définie (auto-expédié), email envoyé
2. **Commande avec ebooks** — vérifie que `AddArticleToUserLibraryUsecase` est
   appelé (mock ou vérifier que les items sont dans la bibliothèque)
3. **Commande avec physique** — vérifie que `shippingDate` n'est PAS définie,
   que les items physiques sont assignés au user
4. **Commande mixte** (ebooks + physique) — vérifie les deux comportements

Utiliser les patterns de `tests/OrderManagerTest.php` (Mockery pour CurrentSite,
UrlGenerator) et `tests/Usecase/` pour la structure.

---

## Phase C — Brancher `MarkOrderAsPayedUsecase` dans `OrderManager`

### C.1 — Transformer `OrderManager::markAsPayed()` en wrapper

**Fichier** : `inc/Order.class.php` (l.564-669)

Remplacer le corps de `markAsPayed()` par :

```php
public function markAsPayed(CurrentSite $currentSite, UrlGenerator $urlGenerator, Order $order): void
{
    $propelOrder = \Model\OrderQuery::create()->findPk($order->get('id'));
    $container = include __DIR__."/../src/container.php";
    $templateService = $container->get("template_service");
    $mailer = $this->getMailer();

    $usecase = new MarkOrderAsPayedUsecase($currentSite, $urlGenerator, $templateService, $mailer);
    $usecase->execute($propelOrder);

    // Recharger l'entité legacy depuis la DB pour synchroniser les champs
    $order->set('order_payment_date', $propelOrder->getPaymentDate()->format('Y-m-d H:i:s'));
    if ($propelOrder->getShippingDate()) {
        $order->set('order_shipping_date', $propelOrder->getShippingDate()->format('Y-m-d H:i:s'));
    }
}
```

**Note** : le `$container` est déjà utilisé dans `addPayment()` (l.553), donc ce
n'est pas un nouveau couplage.

### C.2 — Mettre à jour les tests de `OrderManagerTest`

**Fichier** : `tests/OrderManagerTest.php`

Les tests existants appellent `markAsPayed()` directement. Ils doivent continuer
de passer car le wrapper délègue au usecase. Potentiellement, il faudra mocker
`TemplateService` en plus (vérifier si le container le fournit dans le contexte
de test).

### C.3 — Vérification

```bash
composer test:path tests/OrderManagerTest.php
composer test:path tests/Usecase/MarkOrderAsPayedUsecaseTest.php
composer test
```

---

## Phase D — Créer `AddPaymentToOrderUsecase`

### D.1 — Créer le usecase

**Fichier** : `src/Usecase/AddPaymentToOrderUsecase.php`

```php
class AddPaymentToOrderUsecase
{
    public function __construct(
        private readonly MarkOrderAsPayedUsecase $markOrderAsPayedUsecase,
    ) {}

    public function execute(\Model\Order $order, string $mode, int $amount): void
    {
        // 1. Créer le Payment Propel
        $payment = new \Model\Payment();
        $payment->setOrder($order);
        $payment->setSite($order->getSite());
        $payment->setMode($mode);
        $payment->setAmount($amount);
        $payment->setExecuted(new DateTime());
        $payment->save();

        // 2. Mettre à jour le montant pour ce mode sur la commande
        //    Mapping mode → setter Propel :
        //    "cash" → setPaymentCash(), "cheque" → setPaymentCheque(),
        //    "transfer" → setPaymentTransfer(), "card" → setPaymentCard(),
        //    "paypal" → setPaymentPaypal(), "payplug" → setPaymentPayplug(),
        //    "stripe" → setPaymentCard() (pas de colonne stripe, utiliser card)
        //    Note: vérifier le mapping exact utilisé par le code legacy
        $currentAmount = $this->getPaymentAmountForMode($order, $mode);
        $this->setPaymentAmountForMode($order, $mode, $currentAmount + $amount);

        // 3. Calculer le restant dû
        $remaining = max(0, $order->getAmountTobepaid() - $amount);
        $order->setAmountTobepaid($remaining);

        // 4. Sauvegarder le mode de paiement
        $order->setPaymentMode($mode);
        $order->save();

        // 5. Si entièrement payé → marquer comme payé
        if ($remaining === 0) {
            $this->markOrderAsPayedUsecase->execute($order);
        }
    }

    // Méthodes privées pour le mapping mode → getter/setter Propel
    // (match expression sur $mode)
}
```

**Points d'attention** :

- Le mapping `mode → colonne` est déduit du schema : `order_payment_cash`,
  `order_payment_cheque`, `order_payment_transfer`, `order_payment_card`,
  `order_payment_paypal`, `order_payment_payplug`
- Le code legacy utilise `$order->get('order_payment_'.$mode)` dynamiquement →
  en Propel, faire un `match ($mode)` vers les getters/setters typés
- Le mode `"stripe"` n'a pas de colonne dédiée → vérifier quel mode utilise le
  code Stripe actuel (probablement `"card"` ou une autre convention)
- `\Model\Payment` a déjà `setExecuted()` (via Propel Base, l.695 de
  `Base/Payment.php`)

### D.2 — Créer les tests

**Fichier** : `tests/Usecase/AddPaymentToOrderUsecaseTest.php`

Cas de test :

1. **Paiement partiel** — montant restant > 0, `markAsPayed` PAS appelé
2. **Paiement complet** — montant restant = 0, `markAsPayed` appelé
3. **Paiement qui dépasse** — remaining ne descend pas en dessous de 0
4. **Différents modes** — vérifier le mapping pour cash, cheque, card, paypal,
   payplug

### D.3 — Vérification

```bash
composer test:path tests/Usecase/AddPaymentToOrderUsecaseTest.php
```

---

## Phase E — Migrer les contrôleurs (un par un, chaque étape mergeable)

### E.1 — Migrer le contrôleur Admin (le plus simple)

**Fichier** : `src/AppBundle/Controller/OrderController.php` (l.189-193)

Avant :

```php
$om = new OrderManager();
$orderEntity = $om->getById($id);
$amount = $orderEntity->get('amount_tobepaid');
$om->addPayment($orderEntity, $requestBody->payment_mode, $amount);
```

Après :

```php
$order = OrderQuery::create()->findPk($id);
$usecase = new AddPaymentToOrderUsecase($markOrderAsPayedUsecase);
$usecase->execute($order, $requestBody->payment_mode, $order->getAmountTobepaid());
```

**Note** : il faut que le contrôleur ait accès à `MarkOrderAsPayedUsecase` pour
construire `AddPaymentToOrderUsecase`. Options :

- Injecter les dépendances dans l'action du contrôleur (via les
  ArgumentValueResolvers existants)
- Ou instancier le usecase inline avec les services disponibles

Vérifier que `TemplateService`, `Mailer`, `CurrentSite`, `UrlGenerator` sont
déjà injectables dans ce contrôleur (le contrôleur a déjà `$currentSite`,
`$templateService`, `$mailer` dans la signature de `updateAction`).

### E.2 — Migrer le contrôleur Stripe

**Fichier** : `src/AppBundle/Controller/PaymentController.php` (l.181-199)

Avant :

```php
$om = new OrderManager();
$order = $om->getById($payment->get('order_id'));
$om->addPayment($order, $payment);
```

Ici, `addPayment` reçoit un objet `Payment` legacy. Avec le nouveau usecase, il
faut extraire le mode et le montant :

Après :

```php
$propelOrder = \Model\OrderQuery::create()->findPk($payment->get('order_id'));
$usecase = new AddPaymentToOrderUsecase($markOrderAsPayedUsecase);
$usecase->execute($propelOrder, $payment->get('mode'), $payment->get('amount'));
```

**Note** : le `Payment` legacy est déjà créé avant (l.182 via
`PaymentManager::get()`). Avec le nouveau usecase, le Payment Propel est créé
par le usecase. Il y a donc un doublon : le payment legacy existe déjà en base.
Deux options :

1. **Option A** : ne pas créer un nouveau Payment dans le usecase, mais plutôt
   recevoir un `\Model\Payment` optionnel déjà existant et le mettre à jour
2. **Option B** : le contrôleur récupère le Payment legacy, extrait mode +
   amount, et le usecase crée un nouveau Payment Propel → il faut s'assurer de
   ne pas dupliquer l'enregistrement

**Recommandation** : Option A — ajouter un paramètre optionnel
`?\Model\Payment $existingPayment = null` au usecase. Si fourni, on le met à
jour au lieu d'en créer un nouveau. Cela correspond au cas Stripe/PayPlug où le
Payment est créé en amont.

**Signature ajustée** :

```php
public function execute(
    \Model\Order $order,
    string $mode,
    int $amount,
    ?\Model\Payment $existingPayment = null,
): void
```

### E.3 — Migrer le contrôleur PayPlug

**Fichier** : `src/AppBundle/Controller/OrderController.php` (l.298-301)

Même pattern que Stripe (Payment legacy déjà créé).

Après :

```php
$propelPayment = \Model\PaymentQuery::create()->findPk($payment->get('id'));
$propelOrder = \Model\OrderQuery::create()->findPk($order->get('id'));
$usecase = new AddPaymentToOrderUsecase($markOrderAsPayedUsecase);
$usecase->execute($propelOrder, $propelPayment->getMode(), $propelPayment->getAmount(), $propelPayment);
```

### E.4 — Migrer le contrôleur PayPal

**Fichier** : `src/ApiBundle/Controller/PaymentController.php` (l.146-152)

Avant :

```php
$orderManager = new OrderManager();
$orderEntity = $orderManager->getById($order->getId());
$orderManager->addPayment($orderEntity, "paypal", $paidAmount * 100);
```

Après :

```php
$propelOrder = \Model\OrderQuery::create()->findPk($order->getId());
$usecase = new AddPaymentToOrderUsecase($markOrderAsPayedUsecase);
$usecase->execute($propelOrder, "paypal", (int)($paidAmount * 100));
```

**Note** : ce contrôleur est dans `ApiBundle`, vérifier que les services sont
injectables via les ArgumentResolvers.

---

## Phase F — Nettoyage

### F.1 — Supprimer `OrderManager::addPayment()` et
`OrderManager::markAsPayed()`

**Fichier** : `inc/Order.class.php`

- Supprimer `addPayment()` (l.507-558)
- Supprimer `markAsPayed()` (l.564-669)

### F.2 — Supprimer/migrer les tests legacy

- `tests/OrderTest.php` : les tests `testAddPayment`, `testAddPaymentObject`,
  `testAddPaymentWhenPayed` (l.203-256) testent la méthode supprimée → les
  supprimer ou les migrer en tests du usecase
- `tests/OrderManagerTest.php` : les tests `testMarkAsPayed`,
  `testMarkAsPayedWithEbooks` → supprimer (couverts par les tests du usecase)

### F.3 — Vérification finale

```bash
composer test
```

---

## Résumé de l'ordre (chaque ligne = 1 PR mergeable)

| #  | Étape                                          | Risque                                         | Fichiers principaux                                                                          |
|----|------------------------------------------------|------------------------------------------------|----------------------------------------------------------------------------------------------|
| 0  | 0.1 — Test contrôleur marquage admin payé      | Nul (ajout test)                               | `tests/AppBundle/Controller/OrderControllerTest.php`                                         |
| 1  | A.1 — Méthodes helper sur `\Model\Order`       | Nul (ajout)                                    | `src/Model/Order.php`                                                                        |
| 2  | A.2 — Template Twig email                      | Nul (ajout)                                    | `src/AppBundle/Resources/views/Order/order-paid-email.html.twig`                             |
| 3  | B — `MarkOrderAsPayedUsecase` + tests          | Nul (ajout)                                    | `src/Usecase/MarkOrderAsPayedUsecase.php`, `tests/Usecase/MarkOrderAsPayedUsecaseTest.php`   |
| 4  | C — Wrapper dans `OrderManager::markAsPayed()` | Faible (changement interne, même comportement) | `inc/Order.class.php`, `tests/OrderManagerTest.php`                                          |
| 5  | D — `AddPaymentToOrderUsecase` + tests         | Nul (ajout)                                    | `src/Usecase/AddPaymentToOrderUsecase.php`, `tests/Usecase/AddPaymentToOrderUsecaseTest.php` |
| 6  | E.1 — Migrer Admin                             | Faible                                         | `src/AppBundle/Controller/OrderController.php`                                               |
| 7  | E.2 — Migrer Stripe                            | Faible                                         | `src/AppBundle/Controller/PaymentController.php`                                             |
| 8  | E.3 — Migrer PayPlug                           | Faible                                         | `src/AppBundle/Controller/OrderController.php`                                               |
| 9  | E.4 — Migrer PayPal                            | Faible                                         | `src/ApiBundle/Controller/PaymentController.php`                                             |
| 10 | F — Nettoyage legacy                           | Moyen (suppression)                            | `inc/Order.class.php`, `tests/OrderTest.php`, `tests/OrderManagerTest.php`                   |
