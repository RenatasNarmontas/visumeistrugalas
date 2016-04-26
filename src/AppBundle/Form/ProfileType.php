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

class ProfileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('username')
        ->add('notifications', 'checkbox', array('required' => false, 'label' => 'Email notifications:'))
        ->add('api', 'text', array('label' => 'API key:'));
    }
    public function getParent()
    {
        return 'fos_user_profile';
    }
    public function getName()
    {
        return 'app_user_profile';
    }

}
