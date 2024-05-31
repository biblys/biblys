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

    public function createForBookCollection($collectionName, $publisherName): string
    {
        $collectionSlug = $this->slugify($collectionName);
        $publisherSlug = $this->slugify($publisherName);
        $collectionNameIncludesPublisher = stripos($collectionSlug, $publisherSlug) !== false;
        if ($collectionNameIncludesPublisher) {
            return $this->slugify($collectionName);
        }

        return $this->slugify("$publisherName-$collectionName");
    }

    /**
     * @throws InvalidSlugException
     */
    public
    function validateArticleSlug(string $string): void
    {
        $pattern = "/^[a-z0-9\-]+\/[a-z0-9\-_]+$/";
        if (!preg_match($pattern, $string)) {
            throw new InvalidSlugException("Invalid article slug");
        }
    }
}
