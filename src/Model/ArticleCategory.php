<?php

namespace Model;

use Biblys\Service\Config;
use Model\Base\ArticleCategory as BaseArticleCategory;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'rayons' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ArticleCategory extends BaseArticleCategory
{
    public function preSave(?ConnectionInterface $con = null): bool
    {
//        $config = new Config();

//        $this->setSiteId($config->get("site"));

        return true;
    }
}
