<?php

namespace Biblys\Service;

/**
 * @deprecated
 */
class SlugService
{
    /**
     * @deprecated
     */
    public function slugify(string $input): string
    {
        trigger_deprecation(
            "biblys/biblys",
            "2.74.0",
            "Biblys\Service\SlugService is deprecated, use Biblys\Service\Slug\SlugService instead",
        );

        $slugService = new Slug\SlugService();
        return $slugService->slugify($input);
    }
}