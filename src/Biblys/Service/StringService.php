<?php

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
}