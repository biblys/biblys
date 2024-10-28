<?php

use Biblys\Contributor\Contributor;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class People extends Entity
{
    protected $prefix = 'people';

    public function getModel(): \Model\People
    {
        $model = new \Model\People();
        $model->setId($this->get("id"));
        $model->setFirstName($this->get("first_name"));
        $model->setLastName($this->get("last_name"));
        $model->setName($this->get("name"));

        return $model;
    }

    /**
     * Returns concatenated first (if exists) and last name
     * @return String people's full name
     */
    public function getName(): string
    {
        if ($this->has('first_name')) {
            return $this->get('first_name').' '.$this->get('last_name');
        }
        return $this->get('last_name');
    }

    /**
     * @throws Exception
     * @deprecated People->hasPhoto is deprecated. Use ImagesService->imageExistsFor instead.
     */
    public function hasPhoto(): bool
    {
        trigger_deprecation(
            "biblys",
            "3.0.0",
            "Using People->hasPhoto is deprecated. Use ImagesService->imageExistsFor instead"
        );

        $photo = $this->getPhoto(ignoreDeprecation: true);

        if ($photo->exists()) {
            return true;
        }

        return false;
    }

    /**
     * @throws Exception
     * @deprecated People->getPhoto is deprecated. Use ImagesService->getImageUrlFor instead.
     */
    public function getPhoto(bool $ignoreDeprecation = false): Media
    {
        if (!$ignoreDeprecation) {
            trigger_deprecation(
                "biblys",
                "3.0.0",
                "Using People->getPhoto is deprecated. Use ImagesService->getImageUrlFor instead"
            );
        }

        if (!isset($this->photo)) {
            $this->photo = new Media('people', $this->get('id'));
        }

        return $this->photo;
    }

    /**
     * Get twitter url from Twitter username prop
     * @return String Twitter url
     */
    public function getTwitterUrl(): string
    {
        $username = substr($this->get('twitter'), 1);
        return 'https://www.twitter.com/'.$username;
    }
}

class PeopleManager extends EntityManager
{
    protected $prefix = 'people',
        $table = 'people',
        $object = 'People';

    /**
     * Get all people related to articles from a publisher
     * @return Contributor[]
     */
    public function getAllFromCatalog(): array
    {
        $am = new ArticleManager();
        $articles = $am->getAll([], [], false);

        $contributors = [];
        foreach ($articles as $article) {
            $contributors = array_merge($contributors, $article->getContributors());
        }

        // Remove duplicate
        $contributorIds = [];
        $uniqueContributors = array_filter($contributors, function($contributor) use
        (&$contributorIds) {
            if (in_array($contributor->getId(), $contributorIds)) {
                return false;
            }
            $contributorIds[] = $contributor->getId();
            return true;
        });

        usort($uniqueContributors, function($a, $b) {
            return strcmp($a->getLastName(), $b->getLastName());
        });

        return $uniqueContributors;
    }

    public function preprocess($entity): Entity
    {
        $last_name = $entity->get('last_name');

        // Uppercase last name
        $entity->set('people_last_name', mb_strtoupper($last_name, 'UTF-8'));

        // Full name
        $full_name = trim($entity->get('first_name').' '.$entity->get('last_name'));
        $entity->set('people_name', $full_name);

        // Alphabetical name
        $alpha_name = trim($entity->get('last_name').' '.$entity->get('first_name'));
        $entity->set('people_alpha', $alpha_name);

        // Create slug
        $entity->set('people_url', makeurl($full_name));

        return $entity;
    }

    public function validate($entity): bool
    {
        if (!$entity->has('last_name')) {
            throw new Exception('Le contributeur doit avoir un nom.');
        }

        if ($entity->has('site') && filter_var($entity->get('site'), FILTER_VALIDATE_URL) === false) {
            throw new Exception('L\'adresse du site est invalide.');
        }

        if ($entity->has('facebook') && !preg_match('/^https:\/\/www.facebook.com\/(.*)/', $entity->get('facebook'))) {
            throw new Exception('L\'adresse de la page Facebook doit commencer par https://www.facebook.com/.');
        }

        if ($entity->has('twitter') && !preg_match('/^@(\w){1,15}$/', $entity->get('twitter'))) {
            throw new Exception('Le compte Twitter doit commencer par @ et ne doit pas dépasser 15 caractères.');
        }

        if (!$entity->has('url')) {
            throw new Exception('Le contributeur doit avoir une url.');
        }

        $otherPeopleWithTheSameName = $this->get([
            'people_url' => $entity->get('url'),
            'people_id' => '!= '.$entity->get('id')
        ]);
        if ($otherPeopleWithTheSameName) {
            $peopleName = $entity->getName();
            throw new ConflictHttpException("Il existe déjà un contributeur avec le nom $peopleName.");
        }

        return true;
    }

    /**
     * Update collection AND articles.
     *
     * @param Entity $entity
     * @param null $reason
     * @return Entity|false
     * @throws Exception
     */
    public function update($entity, $reason = null): bool|Entity
    {
        $people = parent::update($entity, $reason = null);
        if ($people) {
            $rm = new RoleManager();
            $am = new ArticleManager();
            $roles = $rm->getAll(["people_id" => $people->get("id")]);
            foreach($roles as $role) {
                if ($role->has("article_id")) {
                    $articles = $am->getAll(['article_id' => $people->get('id')]);
                    foreach ($articles as $a) {
                        $a->set('article_keywords_generated', null);
                        $a->set('article_collection', $people->get('name'));
                        $am->update($a);
                    }
                }
            }

        }

        return $people;
    }
}
