<?php

namespace Biblys\Service;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QueryParamsService
{
    private array $queryParams = [];

    public function __construct(private readonly Request $request)
    {
    }

    public function parse(array $specs): void
    {
        $params = $this->request->query->all();
        $this->_ensureNoUnexpectedParamIsPresent($params, $specs);
        $params = $this->_ensureSpecificationsAreEnforced($params, $specs);

        $this->queryParams = $params;
    }

    public function get(string $key): string
    {
        return $this->queryParams[$key];
    }

    private function _ensureNoUnexpectedParamIsPresent(array $params, array $specs): void
    {
        foreach ($params as $param => $value) {
            if (!array_key_exists($param, $specs)) {
                throw new BadRequestHttpException("Unexpected parameter '$param'");
            }
        }
    }

    private function _ensureSpecificationsAreEnforced(array $params, array $specs): array
    {
        foreach ($specs as $param => $rules) {

            if (!isset($params[$param])) {
                $isRequired = !isset($rules["default"]);
                if ($isRequired) {
                    throw new BadRequestHttpException("Parameter '$param' is required");
                }

                $params[$param] = $rules["default"];

                continue;
            }

            $value = $params[$param];
            foreach ($rules as $rule => $ruleValue) {

                if ($rule === "default") {
                    continue;
                }

                if ($rule === "type") {
                    if ($ruleValue === "string") {
                        if (!is_string($value)) {
                            throw new BadRequestHttpException(
                                "Parameter '$param' must be of type string"
                            );
                        }
                        continue;
                    }

                    if ($ruleValue === "numeric") {
                        if (!is_numeric($value)) {
                            throw new BadRequestHttpException(
                                "Parameter '$param' must be of type numeric"
                            );
                        }

                        continue;
                    }

                    throw new InvalidArgumentException("Invalid value '$ruleValue' for type rule");
                }

                if ($rule === "mb_min_length") {
                    if (mb_strlen($value) < $ruleValue) {
                        throw new BadRequestHttpException(
                            "Parameter '$param' must be at least $ruleValue characters long"
                        );
                    }
                    continue;
                }

                if ($rule === "mb_max_length") {
                    if (mb_strlen($value) > $ruleValue) {
                        throw new BadRequestHttpException(
                            "Parameter '$param' must be $ruleValue characters long or shorter"
                        );
                    }
                    continue;
                }

                throw new InvalidArgumentException("Unknown validation rule '$rule'");
            }
        }

        return $params;
    }
}