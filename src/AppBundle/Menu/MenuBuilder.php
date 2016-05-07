<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createBreadcrumbMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', array('route' => 'homepage'));
        $menu->setChildrenAttribute('class', 'breadcrumb');

        switch ($request->get('_route')) {
            case 'fos_user_security_login':
                $menu->addChild('Login')
                      ->setCurrent(true)
                      ->setAttribute('class', 'active');
                break;

            case 'fos_user_registration_register':
            case 'fos_user_registration_check_email':
            case 'fos_user_registration_confirmed':
                $menu->addChild('Register')
                      ->setCurrent(true)
                    ->setAttribute('class', 'active');
                break;
            case 'fos_user_profile_edit':
                $menu->addChild('Account')
                     ->setCurrent(true)
                    ->setAttribute('class', 'active');
                break;

            case 'fos_user_resetting_request':
            case 'fos_user_resetting_send_email':
            case 'fos_user_resetting_check_email':
                $menu->addChild('Reset password')
                     ->setCurrent(true)
                     ->setAttribute('class', 'active');
                break;
        }

        return $menu;
    }

}