<?php

/*
 * This file is part of the https://github.com/bjoern-hempel/pimcore-hempel-li.git project.
 *
 * (c) 2023 Björn Hempel <bjoern@hempel.lil>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ContactFormType
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class ContactFormType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @param FormBuilderInterface $builder
     * @param array<string, mixed> $options
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'email',
                'label_attr' => [
                    'class' => '',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.email',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Email([
                        'mode' => 'strict',
                    ])
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'name',
                'label_attr' => [
                    'class' => '',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.name',
                ],
                'constraints' => [
                    new NotBlank()
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => 'subject',
                'label_attr' => [
                    'class' => '',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'placeholder.subject',
                ],
                'constraints' => [
                    new NotBlank()
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'label_attr' => [
                    'class' => '',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'height: 200px',
                    'placeholder' => 'placeholder.message',
                    'rows' => 5,
                ],
                'constraints' => [
                    new NotBlank()
                ],
            ]);
    }
}