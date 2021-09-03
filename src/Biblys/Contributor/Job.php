<?php

namespace Biblys\Contributor;

class Job
{
    private $_id,
        $_neutralName,
        $_feminineName,
        $_masculineName,
        $_onixCode;

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setNeutralName($neutralName)
    {
        $this->_neutralName = $neutralName;
    }

    public function getNeutralName()
    {
        return $this->_neutralName;
    }

    public function setFeminineName($feminineName)
    {
        $this->_feminineName = $feminineName;
    }

    public function getFeminineName()
    {
        return $this->_feminineName;
    }

    public function setMasculineName($masculineName)
    {
        $this->_masculineName = $masculineName;
    }

    public function getMasculineName()
    {
        return $this->_masculineName;
    }

    public function setOnixCode($onixCode)
    {
        $this->_onixCode = $onixCode;
    }

    public function getOnixCode()
    {
        return $this->_onixCode;
    }

    /**
     * @return Job[]
     */
    public static function getAll(): array
    {
        $jobs = [];

        $job = new Job();
        $job->setId(10);
        $job->setNeutralName("Acteur·trice");
        $job->setFeminineName("Actrice");
        $job->setMasculineName("Acteur");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(2);
        $job->setNeutralName("Anthologiste");
        $job->setFeminineName("Anthologiste");
        $job->setMasculineName("Anthologiste");
        $job->setOnixCode("B01");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(1);
        $job->setNeutralName("Auteur·trice");
        $job->setFeminineName("Autrice");
        $job->setMasculineName("Auteur");
        $job->setOnixCode("A01");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(19);
        $job->setNeutralName("Auteur·trice de l'introduction");
        $job->setFeminineName("Autrice de l'introduction");
        $job->setMasculineName("Auteur de l'introduction");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(5);
        $job->setNeutralName("Auteur·trice de la préface");
        $job->setFeminineName("Autrice de la préface");
        $job->setMasculineName("Auteur de la préface");
        $job->setOnixCode("A15");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(14);
        $job->setNeutralName("Auteur·trice de la postface");
        $job->setFeminineName("Autrice de la postface");
        $job->setMasculineName("Auteur de la postface");
        $job->setOnixCode("A19");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(13);
        $job->setNeutralName("Autre auteur·trice");
        $job->setFeminineName("Autre autrice");
        $job->setMasculineName("Autre auteur");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(6);
        $job->setNeutralName("Coloriste");
        $job->setFeminineName("Coloriste");
        $job->setMasculineName("Coloriste");
        $job->setOnixCode("A40");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(11);
        $job->setNeutralName("Éditeur·trice");
        $job->setFeminineName("Éditrice");
        $job->setMasculineName("Éditeur");
        $job->setOnixCode("B01");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(4);
        $job->setNeutralName("Illustrateur·trice (couverture)");
        $job->setFeminineName("Illustratrice (couverture)");
        $job->setMasculineName("Illustrateur (couverture)");
        $job->setOnixCode("A36");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(7);
        $job->setNeutralName("Illustrateur·trice (intérieur)");
        $job->setFeminineName("Illustratrice (intérieur)");
        $job->setMasculineName("Illustrateur (intérieur)");
        $job->setOnixCode("A35");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(9);
        $job->setNeutralName("Illustrateur·trice (animation)");
        $job->setFeminineName("Illustratrice (animation)");
        $job->setMasculineName("Illustrateur (animation)");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(20);
        $job->setNeutralName("Illustrateur·trice (autre)");
        $job->setFeminineName("Illustratrice (autre)");
        $job->setMasculineName("Illustrateur (autre)");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(12);
        $job->setNeutralName("Lecteur·trice");
        $job->setFeminineName("Lectrice");
        $job->setMasculineName("Lecteur");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(21);
        $job->setNeutralName("Maquettiste");
        $job->setFeminineName("Maquettiste");
        $job->setMasculineName("Maquettiste");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(17);
        $job->setNeutralName("Mixage");
        $job->setFeminineName("Mixage");
        $job->setMasculineName("Mixage");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(8);
        $job->setNeutralName("Modérateur·trice");
        $job->setFeminineName("Modératrice");
        $job->setMasculineName("Modérateur");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(16);
        $job->setNeutralName("Musique");
        $job->setFeminineName("Musique");
        $job->setMasculineName("Musique");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(18);
        $job->setNeutralName("Photographe");
        $job->setFeminineName("Photographe");
        $job->setMasculineName("Photographe");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(15);
        $job->setNeutralName("Réalisateur·trice");
        $job->setFeminineName("Réalisatrice");
        $job->setMasculineName("Réalisateur");
        $job->setOnixCode("D01");
        $jobs[] = $job;

        $job = new Job();
        $job->setId(3);
        $job->setNeutralName("Traducteur·trice");
        $job->setFeminineName("Traductrice");
        $job->setMasculineName("Traducteur");
        $job->setOnixCode("B06");
        $jobs[] = $job;

        return $jobs;
    }

    /**
     * @throws UnknownJobException
     */
    public static function getById(int $id): ?Job
    {
        $jobs = self::getAll();

        foreach ($jobs as $job) {
            if ($job->getId() === $id) {
                return $job;
            }
        }

        throw new UnknownJobException(
            sprintf(
                "Cannot find a job for id %s",
                $id
            )
        );
    }
}
