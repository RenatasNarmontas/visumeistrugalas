<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * Class ProfileType
 * @package AppBundle\Form
 */
class ProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username')->remove('email')
            ->add(
                'plainPassword',
                PasswordType::class,
                array(
                    'label' => 'form.new_password:',
                    'translation_domain' => 'FOSUserBundle')
            )
            ->add(
                'notifications',
                CheckboxType::class,
                array(
                    'required' => false,
                    'label' => 'form.notifications',
                    'translation_domain' => 'FOSUserBundle'
                )
            )
            ->add(
                'api',
                TextType::class,
                array(
                    'required' => false,
                    'label' => 'form.api',
                    'translation_domain' => 'FOSUserBundle'
                )
            );
        ;

    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
