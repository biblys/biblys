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

    /**
     * @throws InvalidSlugException
     */
    public function validateArticleSlug(string $string): void
    {
        $pattern = "/^[a-z0-9\-]+\/[a-z0-9\-\_]+$/";
        if (!preg_match($pattern, $string)) {
            throw new InvalidSlugException("Invalid article slug");
        }
    }
}
