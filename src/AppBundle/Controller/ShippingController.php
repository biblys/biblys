<?php

namespace AppBundle\Controller;

use Framework\Controller;

class ShippingController extends Controller
{
    /**
     * GET /admin/shipping.
     */
    public function adminAction()
    {
        $this->setPageTitle('Frais de port');
        $this->auth('admin');

        return $this->render('AppBundle:Shipping:admin.html.twig');
    }

    /**
     * @route /shipping/
     */
    public function allAction()
    {
    }
}
