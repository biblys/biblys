<?php

namespace Framework\Composer;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MessageAccumulator implements OutputInterface
{
    private $messages = [];
    /**
     * @var OutputFormatterInterface
     */
    private $formatter;

    public function __construct()
    {
        $this->formatter = new OutputFormatter();
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return join($this->messages, "\r\n");
    }

    public function write($messages, $newline = false, $options = 0)
    {
        if (is_array($messages)) {
            $this->messages = array_merge($this->messages, $messages);
        } else {
            $this->messages[] = $messages;
        }
    }

    public function writeln($messages, $options = 0)
    {
        $this->write($messages, true, 0);
    }

    public function setVerbosity($level)
    {
        // TODO: Implement setVerbosity() method.
    }

    public function getVerbosity()
    {
        // TODO: Implement getVerbosity() method.
    }

    public function isQuiet()
    {
        // TODO: Implement isQuiet() method.
    }

    public function isVerbose()
    {
        // TODO: Implement isVerbose() method.
    }

    public function isVeryVerbose()
    {
        // TODO: Implement isVeryVerbose() method.
    }

    public function isDebug()
    {
        // TODO: Implement isDebug() method.
    }

    public function setDecorated($decorated)
    {
        // TODO: Implement setDecorated() method.
    }

    public function isDecorated()
    {
        // TODO: Implement isDecorated() method.
    }

    public function setFormatter(OutputFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function getFormatter(): ?OutputFormatterInterface
    {
        return $this->formatter;
    }
}