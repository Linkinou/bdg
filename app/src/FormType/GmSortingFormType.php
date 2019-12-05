<?php

namespace App\FormType;

use App\Entity\Location;
use App\Entity\Persona;
use App\Model\PersonasModel;
use App\Model\GameModel;
use App\Model\JoiningGameModel;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GmSortingFormType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('personas', EntityType::class, [
                'choices' => isset($options['personas']) ? $options['personas'] : null,
                'class' => Persona::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'label' => false
            ])
            ->setMethod('POST')
        ;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => PersonasModel::class,
                'label' => false
            ])
            ->setRequired([
                'personas'
            ])
        ;
    }
}