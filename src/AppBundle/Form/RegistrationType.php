<?php


namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->remove('plainPassword')
            ->add(
                'plainPassword',
                PasswordType::class,
                array(
                    'label' => 'form.password',
                    'translation_domain' => 'FOSUserBundle')
            )
              ->add(
                  'notifications',
                  CheckboxType::class,
                  array(
                    'required' => false,
                    'label' => 'form.notifications_recieve',
                    'translation_domain' => 'FOSUserBundle')
              );
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
