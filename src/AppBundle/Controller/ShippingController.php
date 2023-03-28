<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
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
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     */
    public function adminAction(Request $request): Response
    {
        self::authAdmin($request);
        $request->attributes->set("page_title", "Frais de port");

        return $this->render("AppBundle:Shipping:admin.html.twig");
    }
}
