<?php

namespace Biblys\Service;

use Symfony\Component\HttpFoundation\Request;

class BodyParamsService extends ParamsService
{
    public function __construct(private readonly Request $request)
    {
        $params = $this->request->request->all();
        parent::__construct($params);
    }
}