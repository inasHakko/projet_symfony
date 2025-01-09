<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Tache;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $taches = [
            ['nameTask' => 'Configurer le serveur de production', 'deadLine' => '2025-01-10'],
            ['nameTask' => 'Créer une base de données MySQL', 'deadLine' => '2025-01-11'],
            ['nameTask' => 'Rédiger la documentation technique', 'deadLine' => '2025-01-12'],
            ['nameTask' => 'Intégrer le système d\'authentification', 'deadLine' => '2025-01-13'],
            ['nameTask' => 'Tester les API REST', 'deadLine' => '2025-01-14'],
            ['nameTask' => 'Optimiser les requêtes SQL', 'deadLine' => '2025-01-15'],
            ['nameTask' => 'Créer une interface utilisateur responsive', 'deadLine' => '2025-01-16'],
            ['nameTask' => 'Mettre en place le monitoring', 'deadLine' => '2025-01-17'],
            ['nameTask' => 'Corriger les bugs signalés par les tests', 'deadLine' => '2025-01-18'],
            ['nameTask' => 'Réaliser un audit de sécurité', 'deadLine' => '2025-01-19'],
            ['nameTask' => 'Planifier les sprints pour l\'équipe', 'deadLine' => '2025-01-20'],
            ['nameTask' => 'Déployer une version beta', 'deadLine' => '2025-01-21'],
            ['nameTask' => 'Analyser les retours des utilisateurs', 'deadLine' => '2025-01-22'],
            ['nameTask' => 'Réaliser des tests de charge', 'deadLine' => '2025-01-23'],
            ['nameTask' => 'Concevoir une architecture modulaire', 'deadLine' => '2025-01-24'],
            ['nameTask' => 'Mettre en place un pipeline CI/CD', 'deadLine' => '2025-01-25'],
            ['nameTask' => 'Créer des tests unitaires', 'deadLine' => '2025-01-26'],
            ['nameTask' => 'Configurer le système de logs', 'deadLine' => '2025-01-27'],
            ['nameTask' => 'Créer une charte graphique', 'deadLine' => '2025-01-28'],
            ['nameTask' => 'Intégrer un service de paiement', 'deadLine' => '2025-01-29'],
            ['nameTask' => 'Migrer les données existantes', 'deadLine' => '2025-01-30'],
            ['nameTask' => 'Organiser une réunion de rétrospective', 'deadLine' => '2025-01-31'],
            ['nameTask' => 'Évaluer les outils d\'analyse de performance', 'deadLine' => '2025-02-01'],
            ['nameTask' => 'Développer un module d\'export CSV', 'deadLine' => '2025-02-02'],
            ['nameTask' => 'Rédiger des spécifications fonctionnelles', 'deadLine' => '2025-02-03'],
            ['nameTask' => 'Créer une maquette haute fidélité', 'deadLine' => '2025-02-04'],
            ['nameTask' => 'Réaliser des tests A/B', 'deadLine' => '2025-02-05'],
            ['nameTask' => 'Déployer sur un environnement de staging', 'deadLine' => '2025-02-06'],
            ['nameTask' => 'Former l\'équipe au nouvel outil', 'deadLine' => '2025-02-07'],
            ['nameTask' => 'Archiver les anciennes versions du projet', 'deadLine' => '2025-02-08'],
        ];
        
        foreach ($taches as $tacheData) {
            $tache = new Tache();
            $tache->setNameTask($tacheData['nameTask'])
                  ->setDeadLine($tacheData['deadLine']);

            $manager->persist($tache);
        }

        $manager->flush();
    }
}
