<?php

namespace App\EventListener;
use App\Event\AddPersonneEvent;
use Psr\Log\LoggerInterface;
use App\Event\ListAllPersonneEvent;


class PersonneListener{

    // public function onAddPersonneListener(){

    //     dd('ecouteur personne.add');
    // }
    public function __construct(private LoggerInterface $logger){}

    public function onAddPersonneListener(AddPersonneEvent $event)
    {
        $personne = $event->getPersonne();
        // dd('Écouteur activé pour : ' . $personne->getFirstname());
    }

    public function onListAllPersonneListener(ListAllPersonneEvent $event){
        $this->logger->info('Nombre de personnes dans la base : '. $event->getNbrPersonne());
        // dd('Nombre de personnes dans la base : ' . $event->getNbrPersonne());
    }
}