<?php

namespace App\Form;

use App\Entity\Admin;
use App\Entity\Article;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'is_granted_attribute' => 'ROLE_ADMIN',
                'is_granted_disabled' => $options['is_granted_disabled'],
            ])
            ->add('content', TextareaType::class)
            ->add('internalNote', TextType::class, [
                'is_granted_attribute' => 'ROLE_ADMIN',
                'is_granted_hide' => true,
                'is_granted_disabled' => $options['is_granted_disabled'],
                'required' => false,
            ])
            ->add('author', EntityType::class, [
                'class' => Admin::class,
                'choice_label' => function (Admin $admin) {
                    return $admin->getName();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'is_granted_disabled' => false,
        ]);
    }
}
