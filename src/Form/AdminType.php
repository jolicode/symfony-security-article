<?php

namespace App\Form;

use App\Entity\Admin;
use App\Entity\Article;
use App\Security\RoleMapping;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = array_merge(RoleMapping::ARTICLE, ['ROLE_ADMIN', 'ROLE_EDITOR']);

        $builder
            ->add('name', TextType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => array_combine($roles, $roles),
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
