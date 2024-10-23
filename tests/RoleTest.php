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

require_once "setUp.php";

class RoleTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a role
     */
    public function testCreate()
    {
        $rm = new RoleManager();

        $role = $rm->create();

        $this->assertInstanceOf('Role', $role);

        return $role;
    }

    /**
     * Test getting a role
     * @depends testCreate
     */
    public function testGet(Role $role)
    {
        $rm = new RoleManager();

        $gotRole = $rm->getById($role->get('id'));

        $this->assertInstanceOf('Role', $role);
        $this->assertEquals($role->get('id'), $gotRole->get('id'));

        return $role;
    }

    /**
     * Test updating a role
     * @depends testGet
     */
    public function testUpdate(Role $role)
    {
        $rm = new RoleManager();

        $role->set('job_id', 2);
        $rm->update($role);

        $updatedRole = $rm->getById($role->get('id'));

        $this->assertTrue($updatedRole->has('updated'));
        $this->assertEquals($updatedRole->get('job_id'), 2);

        return $updatedRole;
    }

    /**
     * Test deleting a role
     * @depends testGet
     */
    public function testDelete(Role $role)
    {
        $rm = new RoleManager();

        $rm->delete($role, 'Test entity');

        $roleExists = $rm->getById($role->get('id'));

        $this->assertFalse($roleExists);
    }
}
