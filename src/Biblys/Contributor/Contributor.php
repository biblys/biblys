<?php

namespace Biblys\Contributor;

use InvalidArgumentException;
use Model\People;

class Contributor
{
    private $_people;
    private $_job;
    private $_contributionId;

    public function __construct(People $people, Job $job, int $contributionId)
    {
        $this->_people = $people;
        $this->_job = $job;
        $this->_contributionId = $contributionId;
    }

    public function getJobId(): int
    {
        return $this->_job->getId();
    }

    public function isAuthor(): bool
    {
        return $this->getJobId() === 1;
    }

    public function getRole(): string
    {
        if ($this->_people->getGender() === "F") {
            return $this->_job->getFeminineName();
        }

        if ($this->_people->getGender() === "M") {
            return $this->_job->getMasculineName();
        }

        return $this->_job->getNeutralName();
    }

    public function getContributionId(): int
    {
        return $this->_contributionId;
    }

    public function __call($name, $arguments)
    {
        // contributor.job_name
        if ($name === "job_name") {
            trigger_deprecation(
                "biblys",
                "2.55.0",
                "Contributor.job_name is deprecated. Use Contributor.role instead."
            );
            return $this->getRole();
        }

        // contributor.getFirstName()
        if (method_exists($this->_people, $name)) {
            return $this->_people->$name();
        }

        // contributor.get("first_name")
        if ($name === "get") {
            $property = $arguments[0];
            $methodName = self::_getCamelCaseMethodName($property);
            if (method_exists($this->_people, $methodName)) {
                trigger_deprecation(
                    "biblys",
                    "2.55.0",
                    sprintf(
                        "Contributor.get(\"%s\") is deprecated. Use Contributor.%s() instead.",
                        $property,
                        $methodName,
                    )
                );
                return $this->_people->$methodName();
            }
        }

        // contributor.first_name
        // Legit use in templates, NOT deprecated
        $methodName = self::_getCamelCaseMethodName($name);
        if (method_exists($this->_people, $methodName)) {
            return $this->_people->$methodName();
        }

        throw new InvalidArgumentException("Cannot call unknown method $name on Contributor");
    }

    /**
     * @param $name
     * @return string
     */
    private static function _getCamelCaseMethodName($name): string
    {
        return "get" . str_replace("_", "", ucwords($name, "_"));
    }
}