<?php

namespace Model;

use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testIsPublishedReturnsTrueWhenStatusIsTrue(): void
    {
        // given
        $event = new Event();
        $event->setStatus(true);

        // when
        $isPublished = $event->isPublished();

        // then
        $this->assertTrue($isPublished);
    }

    public function testIsPublishedReturnsFalseWhenStatusNull(): void
    {
        // given
        $event = new Event();

        // when
        $isPublished = $event->isPublished();

        // then
        $this->assertFalse($isPublished);
    }
}
