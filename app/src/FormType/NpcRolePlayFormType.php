<?php

namespace App\FormType;

use App\Entity\GameNpc;
use App\Entity\Persona;
use App\Model\NpcRolePlayModel;
use App\Model\RolePlayModel;
use App\Repository\PersonaRepository;
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
                'class' => Persona::class,
                'query_builder' => function (PersonaRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->andWhere('p.type = :type')
                        ->setParameter('type', Persona::TYPE_NPC)
                        ->orderBy('p.name', 'ASC');
                },
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
            'data_class' => RolePlayModel::class
        ]);
    }
}