<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once('inc/functions.php');

class RoleTest extends PHPUnit_Framework_TestCase
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
