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


namespace Biblys\Service;

use Symfony\Component\String\UnicodeString;

class StringService
{
    private UnicodeString $string;

    public function __construct(string $string)
    {
        $this->string = new UnicodeString($string);
    }

    public function get(): string
    {
        return $this->string;
    }

    public function normalize(): self
    {
        $this->string = $this->string->ascii();

        return $this;
    }

    public function uppercase(): self
    {
        $this->string = $this->string->upper();

        return $this;
    }

    public function lowercase(): self
    {
        $this->string = $this->string->lower();

        return $this;
    }

    public function limit(int $length): self
    {
        $this->string = new UnicodeString(substr($this->string, 0, $length));

        return $this;
    }

    public function alphabetize(): self
    {
        $alphabetized = trim(preg_replace(
            "#^(L'|l'|Le |le |LE |La |la |LA |Les |les |LES )(.*)#",
            '$2, $1',
            $this->string
        ));
        $this->string = new UnicodeString($alphabetized);

        return $this;
    }

    public static function random($length = 16): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $randomString = bin2hex(random_bytes($length));
        return (new StringService($randomString))->limit(16)->get();
    }
}