<?php
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
        $supplier->set('supplier_on_order', 1);
        $sm->update($supplier);

        $updatedSupplier = $sm->getById($supplier->get('id'));

        $this->assertTrue($updatedSupplier->has('updated'));
        $this->assertEquals($updatedSupplier->get('name'), 'Fournitout');
        $this->assertEquals($updatedSupplier->get('on_order'), 1);

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
