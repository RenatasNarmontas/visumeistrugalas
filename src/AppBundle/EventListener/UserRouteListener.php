<?php
/**
 * Created by PhpStorm.
 * User: marina
 * Date: 16.5.6
 * Time: 07.36
 */

namespace AppBundle\EventListener;

use FOS\UserBundle\Model\User;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class UserRouteListener
{
    private $tokenStorageInterface;
    private $routerInterface;

    public function __construct(TokenStorageInterface $tokenStorageInterface, RouterInterface $routerInterface)
    {
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->routerInterface = $routerInterface;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $route = $event->getRequest()->attributes->get('_route');

        if ((null !== $this->tokenStorageInterface->getToken())
            && ($this->tokenStorageInterface->getToken()->getUser() instanceof User
                && $this->isForbiddenPath($route))) {
            $event->setResponse(new RedirectResponse($this->routerInterface->generate('fos_user_profile_edit')));
        }
    }

    private function isForbiddenPath($route)
    {
        switch ($route) {
            case 'fos_user_security_login':
            case 'fos_user_registration_register':
            case 'fos_user_resetting_request':
            case 'fos_user_registration_check_email':
                return true;
            default:
                return false;
        }
    }
}
