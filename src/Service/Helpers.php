<?php

namespace App\Service;
use App\Entity\User;
// use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\SecurityBundle\Security;

class Helpers{
    private Security $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function sayCC(){
        return "Hello, CC!";
    }

    public function getUser(): ?User
    {
        if($this->security->isGranted("ROLE_ADMIN")){
            $user = $this->security->getUser();
            if($user instanceof User){
                return $user;
            }  
        }
    }
}