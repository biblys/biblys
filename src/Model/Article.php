<?php

namespace Model;

use Exception;
use Model\Base\Article as BaseArticle;

class Article extends BaseArticle
{
    /**
     * @throws Exception
     */
    public function addContributor(People $contributor, \Biblys\Contributor\Job $job): Role
    {
        $role = RoleQuery::create()
            ->filterByArticleId($this->getId())
            ->filterByPeopleId($contributor->getId())
            ->filterByJobId($job->getId())
            ->findOne();

        if ($role) {
            return $role;
        }

        $role = new Role();
        $role->setArticleId($this->getId());
        $role->setPeopleId($contributor->getId());
        $role->setJobId($job->getId());
        $role->save();

        return $role;
    }
}
