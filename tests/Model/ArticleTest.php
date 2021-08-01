<?php

namespace Model;

use Biblys\Test\ModelFactory;
use Exception;
use PHPUnit\Framework\TestCase;

require_once __DIR__."/../setUp.php";

class ArticleTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testAddContributor()
    {
        // given
        $article = new Article();
        $contributor = ModelFactory::createPeople();
        $job = \Biblys\Contributor\Job::getById(\Biblys\Contributor\Job::AUTHOR);

        // when
        $article->addContributor($contributor, $job);

        // then
        $role = RoleQuery::create()
            ->filterByArticleId($article->getId())
            ->filterByPeopleId($contributor->getId())
            ->filterByJobId(\Biblys\Contributor\Job::AUTHOR)
            ->findOne();
        $this->assertNotNull($role, "role should have been created");
    }
}
