<?php

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