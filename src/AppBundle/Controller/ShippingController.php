<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Framework\Exception\AuthException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShippingController extends Controller
{
    /**
     * GET /admin/shipping.
     * @throws AuthException
     */
    public function adminAction(Request $request): Response
    {
        $request->attributes->set("page_title", "Frais de port");
        $this->auth("admin");

        return $this->render("AppBundle:Shipping:admin.html.twig");
    }

    /**
     * @route /shipping/
     */
    public function allAction()
    {
    }
}
