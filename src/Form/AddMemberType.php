<?php

namespace App\Form;

use App\Entity\ProfilUser;
use App\Entity\Projects;
use App\Entity\Task;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\Mapping\Entity;
use PhpParser\ErrorHandler\Collecting;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('users', EntityType::class, [
            'class' => ProfilUser::class,
            'choice_label' => 'firstName', // Affiche le nom des utilisateurs
            'placeholder' => 'Sélectionner un utilisateur',
            'required' => true,
            'mapped' => false, // Ne pas lier directement à l'entité Projects
        ])
        ->add('tasks', EntityType::class, [
            'class' => Task::class,
            'choice_label' => 'title', // Modifier selon la propriété de la tâche
            'multiple' => true,
            'expanded' => true, // Checkboxes pour sélection multiple
            'required' => false,
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Ajouter au projet',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projects::class,
        ]);
    }
}
