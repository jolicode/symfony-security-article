<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurityExtension extends AbstractTypeExtension
{
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['is_granted_attribute']) {
            return;
        }

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
            if ($this->isGranted($options)) {
                return;
            }

            $event->setData($event->getForm()->getViewData());
        });
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($this->isGranted($options)) {
            return;
        }

        $this->disableView($view);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'is_granted_attribute' => null,
        ]);
    }

    public function getExtendedType()
    {
        return FormType::class;
    }

    private function isGranted(array $options)
    {
        if (!$options['is_granted_attribute']) {
            return true;
        }

        if ($this->authorizationChecker->isGranted($options['is_granted_attribute'])) {
            return true;
        }

        return false;
    }

    private function disableView(FormView $view)
    {
        $view->vars['attr']['disabled'] = true;

        foreach ($view as $child) {
            $this->disableView($child);
        }
    }
}
