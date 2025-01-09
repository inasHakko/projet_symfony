<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Competence;

class CompetenceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $competences = [
            ['nameCompetence' => 'PHP', 'level' => 'expert'],
            ['nameCompetence' => 'Symfony', 'level' => 'intermediaire'],
            ['nameCompetence' => 'JavaScript', 'level' => 'expert'],
            ['nameCompetence' => 'Angular', 'level' => 'intermediaire'],
            ['nameCompetence' => 'React', 'level' => 'débutant'],
            ['nameCompetence' => 'Node.js', 'level' => 'intermediaire'],
            ['nameCompetence' => 'Python', 'level' => 'expert'],
            ['nameCompetence' => 'Django', 'level' => 'intermediaire'],
            ['nameCompetence' => 'Java', 'level' => 'expert'],
            ['nameCompetence' => 'Spring Boot', 'level' => 'intermediaire'],
            ['nameCompetence' => 'HTML/CSS', 'level' => 'expert'],
            ['nameCompetence' => 'Bootstrap', 'level' => 'intermediaire'],
            ['nameCompetence' => 'SQL', 'level' => 'expert'],
            ['nameCompetence' => 'MongoDB', 'level' => 'débutant'],
            ['nameCompetence' => 'Git', 'level' => 'expert'],
            ['nameCompetence' => 'Docker', 'level' => 'intermediaire'],
            ['nameCompetence' => 'Kubernetes', 'level' => 'débutant'],
            ['nameCompetence' => 'Linux', 'level' => 'expert'],
            ['nameCompetence' => 'AWS', 'level' => 'intermediaire'],
            ['nameCompetence' => 'Azure', 'level' => 'débutant'],
            ['nameCompetence' => 'UI/UX Design', 'level' => 'débutant'],
            ['nameCompetence' => 'SEO', 'level' => 'débutant'],
            ['nameCompetence' => 'C#', 'level' => 'intermediaire'],
            ['nameCompetence' => 'C++', 'level' => 'expert'],
            ['nameCompetence' => 'Flutter', 'level' => 'intermediaire'],
            ['nameCompetence' => 'DevOps', 'level' => 'intermediaire'],
            ['nameCompetence' => 'Data Science', 'level' => 'intermediaire'],
            ['nameCompetence' => 'Machine Learning', 'level' => 'débutant'],
            ['nameCompetence' => 'Blockchain', 'level' => 'débutant'],
            ['nameCompetence' => 'Cybersécurité', 'level' => 'intermediaire'],
        ];
        
        foreach ($competences as $competenceData) {
            $competence = new Competence();
            $competence->setNameCompetence($competenceData['nameCompetence'])
                       ->setLevel($competenceData['level']);

            $manager->persist($competence);
        }

        $manager->flush();
    }
}
