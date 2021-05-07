<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => "Name:",
                    'row_attr' => ['class' => 'form-group'],
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'groups',
                EntityType::class,
                [
                    'label' => false,
                    'row_attr' => ['class' => 'form-group'],
                    'attr' =>
                    [
                        'class' => 'selectpicker form-control',
                        'multiple' => null,
                        'data-live-search' => "true",
                        'title' => "Choose groups to add student",
                        'data-selected-text-format' => 'count > 4',
                        'data-count-selected-text' => '{0} groups selected',
                        'data-size' => '7'
                    ],
                    'class' => Group::class,
                    'required' => false,
                    'multiple' => true,
                    'choice_label' => 'name',
                    'choice_value' => 'id'
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Create',
                    'attr' => ['class' => 'btn btn-primary mt-3']
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
