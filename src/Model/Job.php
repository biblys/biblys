<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


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
