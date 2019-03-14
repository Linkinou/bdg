<?php

namespace App\FormType;

use App\Model\EventRolePlayModel;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventRolePlayFormType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control lead',
                    'placeholder' => 'Titre de l\'évènement'
                ]
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'placeholder' => 'Description de l\'évènement',
                    'class' => 'form-control'
                ]
            ])
            ->add('content', CKEditorType::class)
        ;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventRolePlayModel::class
        ]);
    }
}