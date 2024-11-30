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


namespace Biblys\Noosfere;

use Biblys\Test\ModelFactory;
use Exception;
use Model\BookCollection;
use Model\BookCollectionQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Publisher;
use SimpleXMLElement;

require_once __DIR__ . "/../../setUp.php";

class NoosfereTest extends TestCase
{

    /**
     * @throws PropelException
     */
    function setUp(): void
    {
        BookCollectionQuery::create()->deleteAll();
    }

    /** getOrCreateContributor */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetOrCreateContributorWhenContributorExists()
    {
        // given
        $existingContributor = ModelFactory::createContributor(
            firstName: "Silas",
            lastName: "Coade",
            url: "silas-coade",
        );

        // when
        $returnedContributor = Noosfere::getOrCreateContributor(
            0,
            "Silas",
            "Coade",
        );

        // then
        $this->assertEquals($existingContributor->getId(), $returnedContributor->get("id"));
    }

    /** getOrCreateContributor */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetOrCreateContributorWithoutFirstName()
    {
        // given
        $existingContributor = ModelFactory::createContributor(
            lastName: "Anonyme",
            url: "anonyme",
        );

        // when
        $returnedContributor = Noosfere::getOrCreateContributor(
            0,
            null,
            "Anonyme",
        );

        // then
        $this->assertEquals($existingContributor->getId(), $returnedContributor->get("id"));
    }


    /** #getOrCreateCollection */

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetOrCreateCollection()
    {
        $publisher = ModelFactory::createPublisher(name: "NOOSFERE");

        // when
        $returnedCollection = Noosfere::getOrCreateCollection(
            0,
            "New collection",
            $publisher
        );

        // then
        $this->assertInstanceOf(BookCollection::class, $returnedCollection);
        $this->assertNotNull($returnedCollection->getId());
        $this->assertEquals("New collection", $returnedCollection->getName());
        $this->assertEquals($publisher->getId(), $returnedCollection->getPublisherId());

    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetOrCreateCollectionWhenCollectionExistsWithTheSameName()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "NOOSFERE");
        $existingCollection = ModelFactory::createCollection(
            publisher: $publisher, name: "Already exists with name", noosfereId: 123
        );

        // when
        $returnedCollection = Noosfere::getOrCreateCollection(
            456,
            "Already exists with name",
            $publisher
        );

        // then
        $this->assertInstanceOf(BookCollection::class, $returnedCollection);
        $this->assertEquals($existingCollection->getId(), $returnedCollection->getId());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testGetOrCreateCollectionWhenCollectionExistsWithTheSameNoosfereId()
    {
        // given
        $publisher = ModelFactory::createPublisher(name: "NOOSFERE");
        $existingCollection = ModelFactory::createCollection(
            publisher: $publisher, name: "Already exists with id", noosfereId: 123
        );

        // when
        $returnedCollection = Noosfere::getOrCreateCollection(
            123,
            "Already exists with id",
            $publisher
        );

        // then
        $this->assertInstanceOf(BookCollection::class, $returnedCollection);
        $this->assertEquals($existingCollection->getId(), $returnedCollection->getId());
    }

    /** #buildArticlesFromXml */

    /**
     * @throws Exception
     */
    public function testBuildArticlesFromXml()
    {
        // given
        $rawXml = <<<XML
<Livres xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://www.noosfere.org/biblio/schema/livres.xsd">
<Livre Lien="https://www.noosfere.org/livres/niourf.asp?numlivre=2146610914" IdItem="49462" IdLivre="2146610914">
<Intervenants>
<Intervenant NooId="2147205258" TypeIntervention="Auteur" IdIntervention="1">
<Prenom>Claire</Prenom>
<Nom>DUVIVIER</Nom>
</Intervenant>
<Intervenant NooId="2147196972" TypeIntervention="Illustrateur" IdIntervention="3">
<Prenom>Elena</Prenom>
<Nom>VIEILLARD</Nom>
</Intervenant>
</Intervenants>
<Couverture LienCouverture="https://images.noosfere.org/couv/a/auxforges080-2020.jpg">auxforges080-2020.jpg</Couverture>
<ISBN>978-2-37305-080-6</ISBN>
<TitreBase>Un long voyage</TitreBase>
<Titre>Un long voyage</Titre>
<TitreOriginal/>
<Categorie>19 €</Categorie>
<Reference/>
<ParutionOriginale/>
<Editeur IdEditeur="2078945151">AUX FORGES DE VULCAIN</Editeur>
<Collection IdEditeur="2078945151" IdCollection="1975553696">Fiction</Collection>
<Parution>
<DepotLegalMois>mai</DepotLegalMois>
<DepotLegalAnnee>2020</DepotLegalAnnee>
</Parution>
<Page>320</Page>
<Resume>
<p>ISSU D'UNE FAMILLE DE PÊCHEURS, LIESSE DOIT QUITTER SON VILLAGE NATAL À LA MORT DE SON PÈRE. 
FRUSTE MAIS MALIN, IL PARVIENT À FAIRE SON CHEMIN DANS LE COMPTOIR COMMERCIAL OÙ IL A ÉTÉ PLACÉ. 
AU POINT D'ÊTRE PRIS COMME SECRÉTAIRE PAR MALVINE ZÉLINA DE FÉLARASIE, AMBASSADRICE IMPÉRIALE 
DANS L&amp;rsquo;ARCHIPEL, ARISTOCRATE PROMISE À UN GRAND DESTIN POLITIQUE. DANS LE SILLAGE DE LA
 JEUNE FEMME, LIESSE VA S'EMBARQUER POUR UN AMBITIEUX VOYAGE LOIN DE SES ÎLES ET DEVENIR, AU FIL 
 DES ANS, LE TÉMOIN PRIVILÉGIÉ DE LA FIN D&amp;rsquo;UN EMPIRE.</p> <p><em>[texte du rabat de 
 couverture]</em></p> <p>CLAIRE DUVIVIER EST NÉE EN 1981. ELLE EST ÉDITRICE. <em>UN LONG VOYAGE</em> EST SON PREMIER ROMAN.</p>
</Resume>
<Prix IdPrix="67">
<NomPrix>Elbakin</NomPrix>
<CategoriePrix>Roman français</CategoriePrix>
<AnneePrix>2020</AnneePrix>
</Prix>
</Livre>
</Livres>
XML;
        $xml = new SimpleXMLElement($rawXml);

        // when
        $articles = Noosfere::buildArticlesFromXml($xml);

        // then
        $this->assertEquals([
            [
                "article_title" => "Un long voyage",
                "article_title_original" => "",
                "article_publisher" => "AUX FORGES DE VULCAIN",
                "noosfere_IdEditeur" => "2078945151",
                "article_collection" => "Fiction",
                "noosfere_IdCollection" => "1975553696",
                "article_number" => "",
                "article_item" => "49462",
                "article_noosfere_id" => "2146610914",
                "article_cycle" => "",
                "article_tome" => "",
                "noosfere_IdSerie" => "",
                "article_cover_import" => "https://images.noosfere.org/couv/a/auxforges080-2020.jpg",
                "article_pages" => "320",
                "article_summary" => "<p></p><p>\n  </p><p>\n</p>",
                "article_pubdate" => "--01",
                "article_copyright" => "",
                "article_authors" => "Claire DUVIVIER",
                "article_ean" => "9782373050806",
                "article_price" => null,
                "article_people" => [
                    [
                        "people_first_name" => "Claire",
                        "people_last_name" => "DUVIVIER",
                        "people_name" => "Claire DUVIVIER",
                        "people_role" => "Auteur",
                        "people_noosfere_id" => "2147205258",
                    ], [
                        "people_first_name" => "Elena",
                        "people_last_name" => "VIEILLARD",
                        "people_name" => "Elena VIEILLARD",
                        "people_role" => "Illustrateur",
                        "people_noosfere_id" => "2147196972",
                    ],
                ],
                "article_uid" => "9782373050806",
                "article_import_source" => "noosfere",
            ]
        ], $articles);
    }

    /**
     * @throws Exception
     */
    public function testBuildArticlesFromXmlWithoutAuthor()
    {
        // given
        $rawXml = <<<XML
<Livres xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://www.noosfere.org/biblio/schema/livres.xsd">
<Livre Lien="https://www.noosfere.org/livres/niourf.asp?numlivre=2146637607" IdItem="59091" IdLivre="2146637607">
<Intervenants>
<Intervenant NooId="737" TypeIntervention="Auteur" IdIntervention="1">
<Prenom/>
<Nom>ANTHOLOGIE</Nom>
</Intervenant>
</Intervenants>
<ISBN>979-10-307-0678-9</ISBN>
<TitreBase>Memento Mori</TitreBase>
<Titre>Memento Mori</Titre>
<TitreOriginal/>
<Categorie>20 €</Categorie>
<Reference/>
<ParutionOriginale/>
<Editeur IdEditeur="-24371073">AU DIABLE VAUVERT</Editeur>
<Parution>
<DepotLegalMois />
<DepotLegalAnnee>0</DepotLegalAnnee>
</Parution>
<Page>256</Page>
<Resume/>
</Livre>
</Livres>
XML;
        $xml = new SimpleXMLElement($rawXml);

        // when
        $articles = Noosfere::buildArticlesFromXml($xml);

        // then
        $this->assertEquals("ANONYME", $articles[0]["article_authors"]);
        $this->assertEquals(
            [
                "people_first_name" => null,
                "people_last_name" => "ANONYME",
                "people_name" => "ANONYME",
                "people_role" => "Auteur",
                "people_noosfere_id" => null,
            ], $articles[0]["article_people"][0]);
    }
}
