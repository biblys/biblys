<?php

class TicketComment extends Entity
{
    protected $prefix = 'ticket_comment';
    
}

class TicketCommentManager extends EntityManager
{
    protected $prefix = 'ticket_comment',
			  $table = 'ticket_comment',
			  $object = 'TicketComment';
    
}
