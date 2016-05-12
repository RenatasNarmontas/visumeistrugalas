<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

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

    public function createBreadcrumbMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', array('route' => 'homepage'));
        $menu->setChildrenAttribute('class', 'breadcrumb');

        $request = $requestStack->getCurrentRequest();
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
            case 'contacts':
                $menu->addChild('Contacts')
                    ->setCurrent(true)
                    ->setAttribute('class', 'active');
                break;
            case 'providers_avg':
            case 'providers':
                $menu->addChild('Providers')
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
            case 'api_information':
                $menu->addChild('API Information')
                    ->setCurrent(true)
                    ->setAttribute('class', 'active');
                break;
            case 'admin_apis':
                $menu = $this->factory->createItem('root');

                $menu->addChild('Admin', array('route' => 'admin_main'));
                $menu->setChildrenAttribute('class', 'breadcrumb');
                $menu->addChild('API Management')
                    ->setCurrent(true)
                    ->setAttribute('class', 'active');
                break;
            case 'admin_cities':
                $menu = $this->factory->createItem('root');

                $menu->addChild('Admin', array('route' => 'admin_main'));
                $menu->setChildrenAttribute('class', 'breadcrumb');
                $menu->addChild('City Management')
                    ->setCurrent(true)
                    ->setAttribute('class', 'active');
                break;
            case 'admin_usage_stats':
                $menu = $this->factory->createItem('root');

                $menu->addChild('Admin', array('route' => 'admin_main'));
                $menu->setChildrenAttribute('class', 'breadcrumb');
                $menu->addChild('Usage Statistics')
                    ->setCurrent(true)
                    ->setAttribute('class', 'active');
                break;
            case 'admin_users':
                $menu = $this->factory->createItem('root');

                $menu->addChild('Admin', array('route' => 'admin_main'));
                $menu->setChildrenAttribute('class', 'breadcrumb');
                $menu->addChild('User Management')
                    ->setCurrent(true)
                    ->setAttribute('class', 'active');
                break;
        }

        return $menu;
    }
    
}
