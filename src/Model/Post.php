<?php

namespace Model;

use Model\Base\Post as BasePost;

/**
 * Skeleton subclass for representing a row from the 'posts' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Post extends BasePost
{
    public const STATUS_OFFLINE = false;
    public const STATUS_ONLINE = true;
}
