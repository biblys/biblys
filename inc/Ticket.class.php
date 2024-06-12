<?php

class Ticket extends Entity
{
    protected $prefix = 'ticket';

    public function isResolved()
    {
        return $this->has('resolved');
    }

    public function isClosed()
    {
        return $this->has('closed');
    }
}

class TicketManager extends EntityManager
{
    protected $prefix = 'ticket',
              $table = 'ticket',
              $object = 'Ticket';

    /**
     * Get ticket possible priorities
     * @return {array} : the priorities
     */
    public function getPriorities()
    {
        return [
            0 => 'Bas',
            1 => 'Normal',
            2 => 'Haut',
            3 => 'Urgent'
        ];
    }

    /**
     * Get label for priority value
     */
    public function getPriority($index)
    {
        $priorities = $this->getPriorities();

        // If it exists, return priority
        if (isset($priorities[$index])) {
            return $priorities[$index];
        }

        // Else return last priority
        return $priorities[count($priorities) - 1];
    }

    /**
     * Ticket sorting algorithm
     * @param tickets : array of tickets sorted by creation date
     * @return an array of sorted tickets
     */
    public function sort($tickets)
    {
        $sorted_tickets = [];
        $count = count($tickets);

        // Regroup tickets by users in sub-arrays
        $bugs = [];
        $users = [];
        foreach ($tickets as $ticket) {

            // If ticket is for a bug, prioritize it
            if ($ticket->get('type') == "Bug") {
                $sorted_tickets[] = $ticket;
            } else {
                $users[$ticket->get('user_id')][] = $ticket;
            }
        }

        // Sort tickets in each user array by priority
        foreach ($users as $key => $user) {
            usort($users[$key], function($a, $b) {

                if ($a->get('priority') == $b->get('priority')) {
                    return strcmp($a->get('created'), $b->get('created'));
                }

                return strcmp($b->get('priority'), $a->get('priority'));
            });
        }
        $users = array_values($users);

        // While there is users
        $u = 0; // user pointer
        $i = 0;
        while (count($users) > 0) {

            $i++;

            // Extract first ticket from current user and add to list
            $sorted_tickets[] = array_shift($users[$u]);

            // If current user has more tickets
            if (count($users[$u]) > 0) {

                // If this is the last user, go back to the first user
                if ($u === count($users) - 1) {
                    $u = 0;
                    continue;
                }

                // Else, go to the next user
                $u++;
                continue;
            }

            // No more tickets: remove current user from array
            array_splice($users, $u, 1);

            // If this was the last user, go back to the first user
            if ($u >= count($users) - 1) {
                $u = 0;
                continue;
            }
        }

        return $sorted_tickets;
    }
}
