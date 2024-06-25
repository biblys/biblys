<?php

namespace Biblys\Service;

use Symfony\Component\HttpFoundation\Request;

class QueryParamsService extends ParamsService
{
    public function __construct(private readonly Request $request)
    {
        $rawParams = $this->request->query->all();
        parent::__construct($rawParams);
    }
}