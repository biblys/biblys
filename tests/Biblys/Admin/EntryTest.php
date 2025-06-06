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

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

namespace Biblys\Admin;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__."/../../setUp.php";

class EntryTest extends TestCase
{
    /**
     * Test creating an entry.
     */
    public function testCreate()
    {
        $type = new Entry('Nouvel article');

        $this->assertInstanceOf('Biblys\Admin\Entry', $type);
    }

    public function testSetUrl()
    {
        $entry = new Entry('Nouvel article', ['url' => '/pages/articles']);

        $this->assertEquals('/pages/articles', $entry->getUrl());
    }

    public function testSetPath()
    {
        $entry = new Entry('Rayons', ['path' => 'admin_rayons']);

        $this->assertEquals('admin_rayons', $entry->getPath());
    }

    public function testSetTarget()
    {
        $entry = new Entry('Nouvel article', ['target' => '_blank']);

        $this->assertEquals('_blank', $entry->getTarget());
    }

    public function testSetTargetDefault()
    {
        $entry = new Entry('Nouvel article', []);

        $this->assertEquals('_self', $entry->getTarget());
    }

    public function testSetCategory()
    {
        $entry = new Entry('Nouvel article', ['category' => 'Articles']);

        $this->assertEquals('Articles', $entry->getCategory());
    }

    public function testSetIcon()
    {
        $entry = new Entry('Nouvel article');
        $this->assertEquals('cog', $entry->getIcon());

        $entry = new Entry('Nouvel article', ['icon' => 'user']);
        $this->assertEquals('user', $entry->getIcon());
    }

    public function testSetClass()
    {
        $entry = new Entry('Nouvel article', ['class' => 'admin-entry-class']);

        $this->assertTrue($entry->hasClass());
        $this->assertEquals('admin-entry-class', $entry->getClass());
    }

    public function testgSetTaskCount()
    {
        $entry = new Entry('Nouvel article', ['taskCount' => 5]);

        $this->assertEquals(5, $entry->getTaskCount());
    }

    public function testSetSubscription()
    {
        $entry = new Entry('Commandes', ['subscription' => 'orders']);

        $this->assertEquals('orders', $entry->getSubscription());
    }

    public function testGenerateUrls() {
        // given
        $entryWithPath = new Entry('Rayons', ['path' => 'rayon_index']);
        $entryWithUrl = new Entry('Rayons', ['url' => '/rayons']);
        $generator = $this->createMock(UrlGenerator::class);
        $generator->method('generate')->willReturn('/admin/rayons');

        // when
        $entries = Entry::generateUrlsForEntries([$entryWithPath, $entryWithUrl], $generator);

        // then
        $this->assertEquals('/admin/rayons', $entries[0]->getUrl());
        $this->assertEquals('/rayons', $entries[1]->getUrl());
    }
}
