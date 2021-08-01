<?php

namespace Model;

use Model\Base\Job as BaseJob;

class Job extends BaseJob
{
    public const AUTHOR = 1;
    public const ANTHOLOGIST = 2;
    public const TRANSLATOR = 3;
    public const COVER_ARTIST = 4;
    public const PREFACE = 5;
    public const POSTFACE = 14;
}
