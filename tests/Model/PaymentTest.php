<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Model;

use Biblys\Test\ModelFactory;
use DateTime;
use Model\Map\PaymentTableMap;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

class PaymentTest extends TestCase
{

    /** isExecuted */

    /**
     * @throws PropelException
     */
    public function testIsExecutedReturnsFalseWhenNoExecutionDate(): void
    {
        // given
        $payment = new Payment();

        // when
        $result = $payment->isExecuted();

        // then
        $this->assertFalse($result);
    }

    /**
     * @throws PropelException
     */
    public function testIsExecutedReturnsTrueWhenExecutionDateIsSet(): void
    {
        // given
        $payment = new Payment();
        $payment->setExecuted(new DateTime());

        // when
        $result = $payment->isExecuted();

        // then
        $this->assertTrue($result);
    }

    /** preInsert */

    /**
     * @throws PropelException
     */
    public function testPreInsertBuildsSignatureChain(): void
    {
        // given
        $order = ModelFactory::createOrder();

        // when — trois paiements créés successivement
        $first = ModelFactory::createPayment($order, 1500);
        $second = ModelFactory::createPayment($order, 900);
        $third = ModelFactory::createPayment($order, 300);

        // then — chaque hash est calculable à partir du précédent
        $this->assertSame(PaymentHash::CURRENT_VERSION, $third->getHashVersion());
        $this->assertSame(
            PaymentHash::compute(
                PaymentHash::CURRENT_VERSION,
                $second->getAmount(),
                $second->getCreatedAt(),
                $second->getOrderId(),
                $first->getHash()
            ),
            $second->getHash()
        );
        $this->assertSame(
            PaymentHash::compute(
                PaymentHash::CURRENT_VERSION,
                $third->getAmount(),
                $third->getCreatedAt(),
                $third->getOrderId(),
                $second->getHash()
            ),
            $third->getHash()
        );
    }

    /**
     * @throws PropelException
     */
    public function testPreInsertFloorsCreatedDateToWholeSecondsForHashPortability(): void
    {
        // given — un paiement précédent (pour alimenter le chaînage des hashs)
        $order = ModelFactory::createOrder();
        $first = ModelFactory::createPayment($order, 1500);

        // and — un second paiement dont la date de création porte une sous-seconde
        // >= 0.5 : sur MySQL 8 (arrondi), la valeur stockée serait alors une seconde
        // de plus que la valeur brute ; le hook doit la figer à la seconde entière
        // AVANT le calcul du hash pour rester cohérent avec la valeur stockée quel
        // que soit le moteur.
        $createdAtWithSubSecond = DateTime::createFromFormat(
            "Y-m-d H:i:s.u",
            "2026-03-01 10:00:00.700"
        );

        $second = new Payment();
        $second->setOrder($order);
        $second->setAmount(900);
        $second->setMode(Payment::MODE_STRIPE);
        $second->setProviderId("stripe-5678");
        $second->setCreatedAt($createdAtWithSubSecond);
        $second->save();
        $secondId = $second->getId();

        // when — on relit le paiement depuis la base, en vidant le pool d'instances
        // Propel pour être certain de lire la ligne réellement persistée.
        PaymentTableMap::clearInstancePool();
        $reloaded = PaymentQuery::create()->findPk($secondId);

        // then — la date rechargée est bien à la seconde entière (0 microseconde).
        // Note : sur cette machine de dev (MariaDB, qui tronque au stockage), cette
        // assertion passe même sans le correctif, puisque la colonne ne peut de toute
        // façon pas conserver de fraction de seconde. Elle documente le contrat de
        // stockage et sert de garde-fou si la colonne gagnait un jour une précision
        // fractionnaire. La régression réelle (portabilité MySQL 8) est couverte par
        // le test unitaire testPreInsertFloorsCreatedDateInMemoryBeforeHashing
        // ci-dessous, qui échoue bien sur MariaDB sans le correctif.
        $this->assertSame(0, (int)$reloaded->getCreatedAt()->format("u"));

        // and — le hash stocké est recalculable à partir de la date rechargée,
        // garantissant qu'une vérification d'intégrité future ne signalera pas de
        // falsification à tort.
        $this->assertSame(
            PaymentHash::compute(
                PaymentHash::CURRENT_VERSION,
                $reloaded->getAmount(),
                $reloaded->getCreatedAt(),
                $reloaded->getOrderId(),
                $first->getHash()
            ),
            $reloaded->getHash()
        );
    }

    /**
     * @throws PropelException
     */
    public function testPreInsertFloorsCreatedDateInMemoryBeforeHashing(): void
    {
        // given — un paiement dont la date de création porte une sous-seconde,
        // fixée AVANT l'appel du hook (simule le cas d'un appelant fournissant
        // explicitement une date, ou celui où timestampable a déjà renseigné une
        // valeur avec microsecondes).
        $order = ModelFactory::createOrder();
        $payment = new Payment();
        $payment->setOrder($order);
        $payment->setAmount(500);
        $payment->setMode(Payment::MODE_STRIPE);
        $payment->setProviderId("stripe-floor-test");
        $payment->setCreatedAt(DateTime::createFromFormat(
            "Y-m-d H:i:s.u",
            "2026-03-01 10:00:00.700"
        ));

        // when — on invoque directement le hook, sans passer par save(), afin
        // d'observer l'objet en mémoire indépendamment de tout arrondi/troncature
        // fait par le moteur de base de données au stockage.
        $payment->preInsert();

        // then — la date en mémoire a été figée à la seconde entière : c'est cette
        // assertion qui échoue réellement sur ce poste (MariaDB) sans le correctif,
        // puisqu'avant le correctif l'objet conserve ses microsecondes (.700000)
        // jusqu'au moment du stockage.
        $this->assertSame("000000", $payment->getCreatedAt()->format("u"));
    }
}
