<?php

// namespace App\EventListener;

// use Doctrine\ORM\Event\LifecycleEventArgs;
// use Doctrine\ORM\Event\PreUpdateEventArgs;
// use App\Entity\Personne;

// class TimestampableListener
// {
//     public function prePersist(LifecycleEventArgs $args): void
//     {
//         $entity = $args->getObject();

//         // Vérifie si l'entité est de type Personne
//         if ($entity instanceof Personne) {
//             $entity->setCreatedAt(new \DateTimeImmutable());
//             $entity->setUpdatedAt(new \DateTime());
//         }
//     }

//     public function preUpdate(PreUpdateEventArgs $args): void
//     {
//         $entity = $args->getObject();

//         // Vérifie si l'entité est de type Personne
//         if ($entity instanceof Personne) {
//             $entity->setUpdatedAt(new \DateTime());
//         }
//     }
// }
