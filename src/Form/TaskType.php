<?php

namespace App\Form;

use App\Entity\ProfilUser;
use App\Entity\Projects;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => [
                            'class' => 'form-control', // Ajout de la classe Bootstrap
                            'rows' => 5,               // Nombre de lignes par défaut
                            'placeholder' => 'Entrez une description ici...',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Terminée' => 'Terminée',
                    'En cours' => 'En cours',
                    'Non commencé' => 'Non commencé',
                    'En attente' => 'En attente',
                ],
                'attr' => ['class' => 'form-control'], // Pour ajouter une classe CSS
                'placeholder' => 'Sélectionnez un statut', // Optionnel : ajoute une option vide par défaut
                'required' => true, // Définissez si le champ est obligatoire ou non
            ])
            //user 
            ->add('users', EntityType::class, [
                'class' => ProfilUser::class,
                'choice_label' => 'firstName',
                'multiple' => true,
                'attr' => [
                    'class' => 'select2',
                ]
                ]);
            // ->add('project', EntityType::class, [
            //     'class' => Projects::class,
            //     'choice_label' => 'name',
            //     'attr' => ['class' => 'form-control'],
            // ])
            // date de début
            // ->add('start_date', DateType::class, [
            //     'widget' => 'single_text',
            //     'label' => 'Date de début',
            //     'attr' => ['class' => 'form-control'],
            // ])
            // date d'échéance
            // ->add('due_date', DateType::class, [
            //     'widget' => 'single_text',
            //     'label' => 'Date d’échéance',
            //     'attr' => ['class' => 'form-control'],
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
