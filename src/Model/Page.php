<?php

namespace Model;

use Model\Base\Page as BasePage;

/**
 * Skeleton subclass for representing a row from the 'pages' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Page extends BasePage
{
    public function isOnline(): ?bool
    {
        return $this->getStatus();
    }
}
