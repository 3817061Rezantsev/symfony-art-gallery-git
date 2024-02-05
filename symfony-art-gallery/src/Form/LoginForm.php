<?php

namespace Sergo\ArtGallery\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as FormElement;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class LoginForm extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return '';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('POST')->add(
            'login',
            FormElement\TextType::class,
            [
                'required'    => true,
                'label'       => 'Логин',
                'constraints' => [
                    new Assert\Type([
                        'type' => 'string'
                    ])
                ]
            ]
        )->add(
            'password',
            FormElement\TextType::class,
            [
                'required'    => true,
                'label'       => 'Пароль',
                'constraints' => [
                    new Assert\Type([
                        'type' => 'string'
                    ])
                ]
            ]
        )->add(
            'submit',
            FormElement\SubmitType::class,
            [
                'label' => 'Войти'
            ]
        );
        parent::buildForm($builder, $options);
    }
}