<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ResettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plainPassword', PasswordType::class, array('label'=>'form.new_password'));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ResettingFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_resetting';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
