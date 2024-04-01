<?php

namespace Biblys\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Validate\Exception\NamedValueException;
use Validate\Validator;

class QueryParamsService
{
    private array $queryParams = [];

    public function __construct(private readonly Request $request) {}

    public function parse($specs): void
    {
        $validator = new Validator([
            "trim" => true,
            "null_empty_strings" => true,
            "specs" => $specs,
        ]);

        try {
            $this->queryParams = $validator->validate($this->request->query->all());
        } catch (NamedValueException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    public function get(string $key): mixed
    {
        return $this->queryParams[$key];
    }
}