<?php

namespace App\Form;

use App\Entity\Competence;
use App\Entity\Personne;
use App\Entity\Profile;
use App\Entity\Tache;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PersonneFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('name')
            ->add('age')
            ->add('job')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('profile', EntityType::class, [
                'class' => Profile::class,
                'choice_label' => 'nameRS',
            ])
            ->add('taches', EntityType::class, [
                'class' => Tache::class,
                'choice_label' => 'nameTask',
                'multiple' => true,
            ])
            ->add('competences', EntityType::class, [
                'class' => Competence::class,
                'choice_label' => 'nameCompetence',
                'multiple' => true,
            ])
            ->add('edit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
