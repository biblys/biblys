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

class SupplierTest extends PHPUnit\Framework\TestCase
{
    /**
     * Test creating a copy
     */
    public function testCreate()
    {
        $sm = new SupplierManager();

        $supplier = $sm->create();

        $this->assertInstanceOf('Supplier', $supplier);

        return $supplier;
    }

    /**
     * Test getting a copy
     * @depends testCreate
     */
    public function testGet(Supplier $supplier)
    {
        $sm = new SupplierManager();

        $gotSupplier = $sm->getById($supplier->get('id'));

        $this->assertInstanceOf('Supplier', $supplier);
        $this->assertEquals($supplier->get('id'), $gotSupplier->get('id'));

        return $supplier;
    }

    /**
     * Test updating a copy
     * @depends testGet
     */
    public function testUpdate(Supplier $supplier)
    {
        $sm = new SupplierManager();

        $supplier->set('supplier_name', 'Fournitout');
        $sm->update($supplier);

        $updatedSupplier = $sm->getById($supplier->get('id'));

        $this->assertTrue($updatedSupplier->has('updated'));
        $this->assertEquals($updatedSupplier->get('name'), 'Fournitout');

        return $updatedSupplier;
    }

    /**
     * Test deleting a copy
     * @depends testGet
     */
    public function testDelete(Supplier $supplier)
    {
        $sm = new SupplierManager();

        $sm->delete($supplier, 'Test entity');

        $supplierExists = $sm->getById($supplier->get('id'));

        $this->assertFalse($supplierExists);
    }





























}
