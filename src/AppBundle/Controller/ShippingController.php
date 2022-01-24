<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Framework\Exception\AuthException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ShippingController extends Controller
{
    /**
     * GET /admin/shipping.
     * @param Request $request
     * @return Response
     * @throws AuthException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function adminAction(Request $request): Response
    {
        $request->attributes->set("page_title", "Frais de port");
        $this->auth("admin");

        return $this->render("AppBundle:Shipping:admin.html.twig");
    }
}
