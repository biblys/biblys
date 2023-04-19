<?php

namespace Biblys\Service;

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
}
