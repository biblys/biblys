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


namespace AppBundle\Resources\views;

use Biblys\Test\EntityFactory;
use Biblys\Test\Helpers;
use Exception;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require_once __DIR__ . "/../../../setUp.php";

class GalleryTemplatesTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testIndexTemplate()
    {
        // given
        $templateService = Helpers::getTemplateService();
        $gallery = EntityFactory::createGallery(title: "Les Photos", mediaDir: "photos");
        EntityFactory::createMediaFile(directory: "photos");

        // when
        $html = $templateService->render('AppBundle:Gallery:index.html.twig', [
            'galleries' => [$gallery],
        ]);

        // then
        $this->assertStringContainsString("Les Photos", $html);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws PropelException
     * @throws Exception
     */
    public function testShowTemplate()
    {
        // given
        $templateService = Helpers::getTemplateService();
        $gallery = EntityFactory::createGallery(title: "Les Photos", mediaDir: "photos");
        EntityFactory::createMediaFile(directory: "photos");

        // when
        $html = $templateService->render('AppBundle:Gallery:show.html.twig', [
            'gallery' => $gallery,
        ]);

        // then
        $this->assertStringContainsString("Les Photos", $html);
    }
}