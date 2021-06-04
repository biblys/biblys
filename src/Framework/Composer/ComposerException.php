<?php


namespace Framework\Composer;

use Exception;
use Throwable;

class ComposerException extends Exception
{
    /**
     * @var string
     */
    private $output;

    public function __construct($message, $output, $code = 0, Throwable $previous = null)
    {
        $this->output = $output;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }
}