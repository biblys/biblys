<?php

namespace Biblys\Service\Slug;

use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\UnicodeString;

class SlugService
{
    public function slugify(string $input): string
    {
        $replacements = [
            "&" => "et"
        ];
        $slugger = new AsciiSlugger("fr", ["fr" => $replacements]);
        $string = new UnicodeString($input);
        $lowercaseString = $string->lower();
        return $slugger->slug($lowercaseString);
    }
}
