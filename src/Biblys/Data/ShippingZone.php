<?php

namespace Biblys\Data;

use Model\Country;
use Model\CountryQuery;

class ShippingZone
{
    private string $_code;
    private string $_description;

    public function getCode(): string
    {
        return $this->_code;
    }

    public function setCode(string $code): void
    {
        $this->_code = $code;
    }

    public function getDescription(): string
    {
        return $this->_description;
    }

    public function setDescription(string $description): void
    {
        $this->_description = $description;
    }

    /**
     * @return Country[]
     */
    public function getCountries(): array
    {
        return CountryQuery::create()->findByShippingZone($this->getCode())->getArrayCopy();
    }

    /**
     * @return ShippingZone[]
     */
    public static function getAll(): array
    {
        $zones = [];

        $zoneF = new ShippingZone();
        $zoneF->setCode("F");
        $zoneF->setDescription("France métropolitaine, Andorre, Monaco");
        $zones[] = $zoneF;

        $zoneA = new ShippingZone();
        $zoneA->setCode("A");
        $zoneA->setDescription("Union Européenne, Suisse, Royaume-Uni, Liechtenstein, Saint-Marin et le Vatican.");
        $zones[] = $zoneA;

        $zoneB = new ShippingZone();
        $zoneB->setCode("B");
        $zoneB->setDescription("Europe de l’Est (hors UE, Suisse et Russie), Norvège et Maghreb.");
        $zones[] = $zoneB;

        $zoneC = new ShippingZone();
        $zoneC->setCode("C");
        $zoneC->setDescription("Autres destinations (hors UE et Suisse).");
        $zones[] = $zoneC;

        $zoneOM1 = new ShippingZone();
        $zoneOM1->setCode("OM1");
        $zoneOM1->setDescription("Guadeloupe, Martinique, Guyane, La Réunion, Mayotte, Saint-Pierre-et-Miquelon, Saint-Martin, Saint-Barthélemy.");
        $zones[] = $zoneOM1;

        $zoneOM2 = new ShippingZone();
        $zoneOM2->setCode("OM2");
        $zoneOM2->setDescription("Polynésie française, Nouvelle-Calédonie, Wallis et Futuna, Terres australes et antarctiques françaises");
        $zones[] = $zoneOM2;

        return $zones;
    }
}