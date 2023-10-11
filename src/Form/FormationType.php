<?php

namespace App\Form;


use DateTime;

use App\Entity\Formation;
use App\Entity\Categorie;
use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType ;



class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('publishedAt', DateType::class, [
            'widget' => 'single_text',
            'data' => isset($options['data']) && $options['data']->getPublishedAt()
            ? $options['data']->getPublishedAt() : new \DateTime('now'),
            'label' => 'Date de publication'
          
        ])

            ->add('title')
            ->add('description', null)
            ->add('videoId')
            ->add('playlist', EntityType::class,[
                'class' => Playlist::class,
                'choice_label' => 'name',
                'label' => 'Playlist',
                'multiple' => false,
                'required' => true,
                'invalid_message' => 'Veuillez choisir une playlist'
            ])
            ->add('categories',  EntityType::class,[
                'class' => Categorie::class,
                'choice_label' => 'name',
                'label' => 'Catégorie(s)',
                'multiple' => true,
                'required' => false,
                'expanded' => true,
                'attr' => ['class' => 'mb-2']
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }





    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
