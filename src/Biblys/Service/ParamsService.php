<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


namespace Biblys\Service;

use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class ParamsService
{
    private array $params = [];

    public function __construct(private readonly array $rawParams)
    {
    }

    public function parse(array $specs): void
    {
        $this->_ensureNoUnexpectedParamIsPresent($this->rawParams, $specs);
        $params = $this->_ensureSpecificationsAreEnforced($this->rawParams, $specs);

        $this->params = $params;
    }

    public function get(string $key): ?string
    {
        return $this->params[$key];
    }

    public function getInteger(string $key): ?int
    {
        $value = $this->get($key);

        if (!is_numeric($value) && !is_null($value)) {
            throw new InvalidArgumentException(
                "Cannot get non numeric parameter '$key' as an integer"
            );
        }

        if (is_null($value)) {
            return null;
        }

        return intval($value);
    }

    public function getBoolean(string $key): bool
    {
        $value = $this->get($key);

        if (is_string($value)) {
            $value = strtolower($value);
            if ($value === "true" || $value === "1" || $value === "yes" || $value === "on") {
                return true;
            }
        }

        return false;
    }

    protected function _ensureNoUnexpectedParamIsPresent(array $params, array $specs): void
    {
        foreach ($params as $param => $value) {
            if (!array_key_exists($param, $specs)) {
                throw new BadRequestHttpException("Unexpected parameter '$param'");
            }
        }
    }

    protected function _ensureSpecificationsAreEnforced(array $params, array $specs): array
    {

        foreach ($specs as $param => $rules) {

            $isOptional = array_key_exists("default", $rules);
            $isMissing = !isset($params[$param]) || $params[$param] === "";
            if ($isMissing) {
                if (!$isOptional) {
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

                        $utf8Value = mb_convert_encoding($value, "UTF-8", "UTF-8");
                        if ($utf8Value !== $value) {
                            throw new BadRequestHttpException("Malformed UTF characters in parameter '$param'");
                        }

                        continue;
                    }

                    if ($ruleValue === "numeric") {
                        if (!is_numeric($value) && !empty($value)) {
                            throw new BadRequestHttpException(
                                "Parameter '$param' must be of type numeric"
                            );
                        }

                        continue;
                    }

                    if ($ruleValue === "boolean") {
                        $acceptedValue = ["true", "false", "1", "0", "yes", "no", "on", "off", ""];
                        if (!in_array($value, $acceptedValue, true)) {
                            throw new BadRequestHttpException(
                                "Parameter '$param' must be of type boolean"
                            );
                        }

                        continue;
                    }

                    throw new InvalidArgumentException("Invalid value '$ruleValue' for type rule");
                }

                if ($rule === "mb_min_length") {
                    $ruleShouldBeIgnoredBecauseDefaultValueIsUsed = $isOptional && $value === "";
                    if ($ruleShouldBeIgnoredBecauseDefaultValueIsUsed) {
                        continue;
                    }
                    
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

                if ($rule === "min") {
                    if (intval($value) < $ruleValue) {
                        throw new BadRequestHttpException("Parameter '$param' cannot be less than $ruleValue");
                    }

                    continue;
                }

                throw new InvalidArgumentException("Unknown validation rule '$rule'");
            }
        }

        return $params;
    }
}