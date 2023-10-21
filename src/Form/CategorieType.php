<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;


class CategorieType extends AbstractType
  {
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }
    


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom de la Catégorie',
            'required' => true,
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le nom de la catégorie ne peut pas être vide.']),
            ],
        ])

        ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit'])

            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary']
            

        ])
            
        ;
    }



    /**
     * @param FormEvent $event
     * Génére un message d'erreur si le nom de la catégorie est déjà existant
     * @return [type]
     */
    public function onPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // Vérifiez l'unicité ici
        $existingCategorie = $this->categorieRepository->findOneBy(['name' => $data['name']]);

        if ($existingCategorie) {
            $form->addError(new FormError('Ce nom de catégorie existe déjà.'));
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
            'validation_groups' => ['Default', 'votre_groupe'],
        
           // 'validation_groups' => ['Default', 'submit']
        ]);
    }
}
