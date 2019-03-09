<?php

namespace App\FormType;

use App\Entity\Location;
use App\Entity\Persona;
use App\Model\GameModel;
use App\Model\JoiningGameModel;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JoiningGameFormType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Persona[] $userPersonas */
        $userPersonas = $options['user_personas'];
        $actionUrl = $options['action_url'];

        $builder
            ->add('persona', ChoiceType::class, [
                'choices' => $userPersonas,
                'choice_label' => function(Persona $persona) {
                    return $persona->getName();
                },
                'choice_value' => function(Persona $persona = null) {
                    return $persona ? $persona->getId() : '';
                },
            ])
            ->setAction($actionUrl)
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
                'data_class' => JoiningGameModel::class,
            ])
            ->setRequired([
                'action_url',
                'user_personas'
            ])
        ;
    }
}