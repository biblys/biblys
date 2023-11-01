<?php

namespace Biblys\Service;

use Biblys\Service\Slug\InvalidSlugException;
use Biblys\Service\Slug\SlugService;
use PHPUnit\Framework\TestCase;

class SlugServiceTest extends TestCase
{
    public function testWithAsciiString()
    {
        // given
        $slugService = new SlugService();

        // when
        $slug = $slugService->slugify("Welcome to Biblys");

        // then
        $this->assertEquals("welcome-to-biblys", $slug);
    }

    public function testWithFrenchAccentuatedText()
    {
        // given
        $slugService = new SlugService();

        // when
        $slug = $slugService->slugify("Élémentaire, mon cher Watson.");

        // then
        $this->assertEquals("elementaire-mon-cher-watson", $slug);
    }

    public function testWithAmpersandCaracter()
    {
        // given
        $slugService = new SlugService();

        // when
        $slug = $slugService->slugify("Fruits & légumes");

        // then
        $this->assertEquals("fruits-et-legumes", $slug);
    }

    /**
     * @throws InvalidSlugException
     */
    public function testValidateArticleSlugSuccess()
    {
        // given
        $slugService = new SlugService();

        // then
        $this->expectNotToPerformAssertions();

        // when
        $slugService->validateArticleSlug("walter-jon-williams/la-peste-du-leopard-vert");
    }

    public function testValidateArticleSlugFailure()
    {
        // given
        $slugService = new SlugService();

        // then
        $this->expectException(InvalidSlugException::class);

        // when
        $slugService->validateArticleSlug("articles/搭建六合源码论坛【联系TG:bc3979】n搭建六合源码论坛【联系TG:bc3979】nj");
    }
}
