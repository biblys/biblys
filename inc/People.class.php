<?php

use Biblys\Contributor\Contributor;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class People extends Entity
    {
        protected $prefix = 'people';

        /**
         * Returns concatenated first (if exists) and last name
         * @return String people's full name
         */
        public function getName()
        {
            if ($this->has('first_name')) {
                return $this->get('first_name').' '.$this->get('last_name');
            }
            return $this->get('last_name');
        }

        /**
         * Returns true if author's photo exists
         * @return boolean
         */
        public function hasPhoto() {
            $photo = $this->getPhoto();

            if ($photo->exists()) {
                return true;
            }

            return false;
        }

        /**
         * Get people photo
         * @return Media the media object for the photo, or false
         */
        public function getPhoto()
        {
            if (!isset($this->photo)) {
                $this->photo = new Media('people', $this->get('id'));
            }

            return $this->photo;
        }

        /**
         * Save uploaded file as contributor's photo
         * @param UploadedFile $file a file that was uploaded
         * @return Media             the contributor's saved Media
         */
        public function addPhoto($file)
        {
            if ($file->getMimeType() !== 'image/jpeg') {
                throw new Exception('La photo doit être au format JPEG.');
            }

            $photo = new Media('people', $this->get('id'));
            $photo->upload($file->getRealPath());

            return $photo;
        }

        /**
         * Get twitter url from Twitter username prop
         * @return String Twitter url
         */
        public function getTwitterUrl()
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

        public function preprocess($people)
        {
            $last_name = $people->get('last_name');

            // Uppercase last name
            $people->set('people_last_name', mb_strtoupper($last_name, 'UTF-8'));

            // Full name
            $full_name = trim($people->get('first_name').' '.$people->get('last_name'));
            $people->set('people_name', $full_name);

            // Alphabetical name
            $alpha_name = trim($people->get('last_name').' '.$people->get('first_name'));
            $people->set('people_alpha', $alpha_name);

            // Create slug
            $people->set('people_url', makeurl($full_name));

            return $people;
        }

        public function validate($people)
        {
            if (!$people->has('last_name')) {
                throw new Exception('Le contributeur doit avoir un nom.');
            }

            if ($people->has('site') && filter_var($people->get('site'), FILTER_VALIDATE_URL) === false) {
                throw new Exception('L\'adresse du site est invalide.');
            }

            if ($people->has('facebook') && !preg_match('/^https:\/\/www.facebook.com\/(.*)/', $people->get('facebook'))) {
                throw new Exception('L\'adresse de la page Facebook doit commencer par https://www.facebook.com/.');
            }

            if ($people->has('twitter') && !preg_match('/^@(\w){1,15}$/', $people->get('twitter'))) {
                throw new Exception('Le compte Twitter doit commencer par @ et ne doit pas dépasser 15 caractères.');
            }

            if (!$people->has('url')) {
                throw new Exception('Le contributeur doit avoir une url.');
            }

            $otherPeopleWithTheSameName = $this->get([
                'people_url' => $people->get('url'),
                'people_id' => '!= '.$people->get('id')
            ]);
            if ($otherPeopleWithTheSameName) {
                $peopleName = $people->getName();
                throw new ConflictHttpException("Il existe déjà un contributeur avec le nom $peopleName.");
            }

            return true;
        }
    }
