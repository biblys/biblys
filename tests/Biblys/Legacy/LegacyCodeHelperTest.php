<?php
/*
 * Copyright (C) 2026 Clément Latzarus
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


namespace Biblys\Legacy;

use Biblys\Test\ModelFactory;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;

require_once __DIR__."/../../setUp.php";

class LegacyCodeHelperTest extends TestCase
{
    /**
     * The legacy site built by setGlobalSite must expose site_tva, which the
     * tax rate calculation (StockManager::getTaxRate) reads to compute VAT.
     *
     * @throws PropelException
     */
    public function testSetGlobalSitePreservesTva()
    {
        // given
        $site = ModelFactory::createSite();
        $site->setTva("fr");
        $site->save();

        // when
        LegacyCodeHelper::setGlobalSite($site);
        $globalSite = LegacyCodeHelper::getGlobalSite(ignoreDeprecation: true);

        // then
        $this->assertEquals("fr", $globalSite->get("site_tva"));
    }
}
