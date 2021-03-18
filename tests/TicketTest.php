<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class TicketTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test creating a copy
     */
    public function testCreate()
    {
        $tm = new TicketManager();

        $ticket = $tm->create();

        $this->assertInstanceOf('Ticket', $ticket);

        return $ticket;
    }

    /**
     * Test getting a copy
     * @depends testCreate
     */
    public function testGet(Ticket $ticket)
    {
        $tm = new TicketManager();

        $gotTicket = $tm->getById($ticket->get('id'));

        $this->assertInstanceOf('Ticket', $ticket);
        $this->assertEquals($ticket->get('id'), $gotTicket->get('id'));

        return $ticket;
    }

    /**
     * Test updating a copy
     * @depends testGet
     */
    public function testUpdate(Ticket $ticket)
    {
        $tm = new TicketManager();

        $ticket->set('ticket_title', 'Tickettou');
        $tm->update($ticket);

        $updatedTicket = $tm->getById($ticket->get('id'));

        $this->assertTrue($updatedTicket->has('updated'));
        $this->assertEquals($updatedTicket->get('title'), 'Tickettou');

        return $updatedTicket;
    }

    /**
     * Test isResolved method
     */
    public function testIsResolved()
    {
        $tm = new TicketManager();
        $ticket = $tm->create();

        $this->assertFalse($ticket->isResolved());

        $ticket->set('resolved', date('Y-m-d H:i:s'));
        $this->assertTrue($ticket->isResolved());
    }

    /**
     * Test isClosed method
     */
    public function testIsClosed()
    {
        $tm = new TicketManager();
        $ticket = $tm->create();

        $this->assertFalse($ticket->isClosed());

        $ticket->set('closed', date('Y-m-d H:i:s'));
        $this->assertTrue($ticket->isClosed());
    }

    /**
     * Test deleting a copy
     * @depends testGet
     */
    public function testDelete(Ticket $ticket)
    {
        $tm = new TicketManager();

        $tm->delete($ticket, 'Test entity');

        $ticketExists = $tm->getById($ticket->get('id'));

        $this->assertFalse($ticketExists);
    }
}
