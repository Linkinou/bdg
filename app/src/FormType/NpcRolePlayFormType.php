<?php

namespace App\FormType;

use App\Entity\GameNpc;
use App\Model\NpcRolePlayModel;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NpcRolePlayFormType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('npc', EntityType::class, [
                'class' => GameNpc::class,
                'choice_label' => 'name',
                'attr' => [
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
            'data_class' => NpcRolePlayModel::class
        ]);
    }
}