<?php

namespace AppBundle\Controller;

use Framework\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Routing\Exception\ResourceNotFoundException as NotFoundException;

class LinkController extends Controller
{

    /**
     * Delete a link
     * /links/{id}/delete
     * @param  $id The id of to the link to be deleted
     */
    public function deleteAction($id)
    {
        $this->auth("admin");

        $lm = $this->entityManager("Link");

        $link = $lm->getById($id);
        if (!$link) {
            throw new NotFoundException("Link $id not found.");
        }

        $lm->delete($link);

        return new JsonResponse();
    }
}
