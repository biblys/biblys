<?php

namespace Model;

use Biblys\Data\FileType;
use Model\Base\File as BaseFile;

/**
 * Skeleton subclass for representing a row from the 'files' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class File extends BaseFile
{
    public const ACCESS_PUBLIC = 0;
    public const ACCESS_RESTRICTED = 1;

    public function getFileType(): FileType
    {
        return FileType::getByMediaType($this->getType());
    }
}
