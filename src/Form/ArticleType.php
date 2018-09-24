<?php

namespace App\Form;

use App\Entity\Admin;
use App\Entity\Article;
use App\Security\RoleMapping;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ArticleType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'is_granted_disabled' => $options['is_granted_disabled'],
                'is_granted_attribute' => 'ARTICLE_EDIT',
                'is_granted_subject_path' => 'parent.data',
            ])
            ->add('content', TextareaType::class)
            ->add('internalNote', TextType::class, [
                'is_granted_attribute' => 'ROLE_ADMIN',
                'is_granted_hide' => true,
                'is_granted_disabled' => $options['is_granted_disabled'],
                'required' => false,
            ])
            ->add('category', ChoiceType::class, [
                'choices' => $this->buildCategoryChoices($options),
                'is_granted_attribute' => 'ROLE_ADMIN',
                'is_granted_disabled' => $options['is_granted_disabled'],
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

    private function buildCategoryChoices(array $options): array
    {
        // special case: if the form is in an "edit" mode, we return all
        // categories since the the form will be disabled anyway
        if (!$options['is_granted_disabled']) {
            return array_combine(Article::CATEGORIES, Article::CATEGORIES);
        }

        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return [];
        }

        $admin = $token->getUser();
        if (!$admin instanceof Admin) {
            return [];
        }

        $roles = $admin->getRoles();
        if (\in_array('ROLE_ADMIN', $roles, true)) {
            return array_combine(Article::CATEGORIES, Article::CATEGORIES);
        }

        $categories = [];

        foreach (RoleMapping::ARTICLE as $category => $role) {
            if (\in_array($role, $roles, true)) {
                $categories[]  = $category;
            }
        }

        return array_combine($categories, $categories);
    }
}
