<?php

namespace AppBundle\Controller;

use Framework\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class MaintenanceController extends Controller
{
    public function infosAction(): JsonResponse
    {
        return new JsonResponse([
            'version' => BIBLYS_VERSION,
        ]);
    }
}
