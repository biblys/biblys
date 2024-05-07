<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentUser;
use Exception;
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
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     * @throws Exception
     */
    public function adminAction(
        Request     $request,
        CurrentUser $currentUser,
    ): Response
    {
        $currentUser->authAdmin();

        $request->attributes->set("page_title", "Frais de port");

        return $this->render("AppBundle:Shipping:admin.html.twig");
    }
}
