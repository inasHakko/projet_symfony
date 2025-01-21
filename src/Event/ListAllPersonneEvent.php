<?php

namespace App\Event;
use Symfony\Contracts\EventDispatcher\Event;
use App\Entity\Personne;

class ListAllPersonneEvent extends Event{

    const LIST_ALL_PERSONNE_EVENT = 'personne.list_all';

    public function __construct(private int $nbrPersonne){}

    public function getNbrPersonne(): int {
        return $this->nbrPersonne;
    }
}

