# ADR-0001 — Conventions pour les usecases

**Date :** 2026-06-21
**Statut :** Accepté

---

## Contexte

Biblys migre progressivement vers une Clean Architecture. Les usecases constituent la couche application qui encapsule la logique métier. Plusieurs questions de conception se posent lors de l'introduction de chaque nouveau usecase.

---

## Décisions

### 1. Les usecases reçoivent un ID, pas un objet

```php
// ✅
$usecase->execute(int $orderId): void

// ❌
$usecase->execute(Model\Order $order): void
```

**Pourquoi :** En Clean Architecture, le controller n'est qu'un adaptateur HTTP. Il ne doit pas manipuler des objets du domaine ou de l'ORM. Passer un ID (primitive) découple le usecase du framework et le rend testable indépendamment. Le usecase est responsable de fetcher lui-même l'entité dont il a besoin.

---

### 2. Les usecases sont propriétaires de toute la logique métier — lecture et écriture

Le usecase lit les données nécessaires à sa décision (via des repositories) et écrit le résultat. Ce n'est pas « le controller lit, le usecase écrit » — c'est « le usecase possède la règle métier de bout en bout ».

```php
// ✅ Le usecase lit les paiements pour décider, puis persiste l'annulation
public function execute(int $orderId): void
{
    $order = OrderQuery::create()->findPk($orderId);
    $payments = $this->paymentRepository->findExecutedByOrder($order);
    // ... validation ...
    $order->setCancelDate(new DateTime());
    $order->save();
}
```

---

### 3. Les usecases sont instanciés inline dans les controllers

```php
// ✅
$cancelOrderUsecase = new CancelOrderUsecase(new PaymentRepository());
$cancelOrderUsecase->execute($id);

// ❌ (non disponible pour l'instant)
$usecase->execute($id);  // injecté via container
```

**Pourquoi :** Le container du projet (Symfony ContainerBuilder avec `ArgumentValueResolverInterface`) n'injecte que des services d'infrastructure. Créer un resolver par usecase serait une nouvelle convention à maintenir. L'injection via container est la cible à moyen terme, mais cette migration se fera globalement — pas usecase par usecase.

---

### 4. Les usecases lèvent `BusinessRuleException` pour les violations de règles métier

```php
throw new BusinessRuleException("Cette commande ne peut pas être annulée...");
```

Le controller adapte cette exception à son contexte HTTP :

```php
} catch (BusinessRuleException $e) {
    throw new BadRequestHttpException($e->getMessage());
}
```

Cela garde le usecase indépendant de Symfony.

---

### 5. La responsabilité du controller se limite à l'adaptation HTTP

| Responsabilité | Controller | Usecase |
|---|---|---|
| Parser la requête HTTP | ✅ | ❌ |
| Authentifier | ✅ | ❌ |
| Passer l'ID au usecase | ✅ | ❌ |
| Fetcher l'entité principale | ❌ | ✅ |
| Lire les données auxiliaires | ❌ | ✅ |
| Appliquer les règles métier | ❌ | ✅ |
| Persister | ❌ | ✅ |
| Formater la réponse HTTP | ✅ | ❌ |
| Connaître `Request`/`Response` | ✅ | ❌ |

---

## Conséquences

- La logique métier est centralisée et testable en isolation sans HTTP.
- Un même usecase peut être appelé depuis plusieurs points d'entrée (controller Symfony, page legacy, commande console) sans duplication.
- La migration vers l'injection via container se fera en une seule vague quand le projet s'y attaquera, sans refactoring des usecases eux-mêmes.
