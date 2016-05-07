<?php
/**
 * Created by PhpStorm.
 * User: marina
 * Date: 16.5.6
 * Time: 23.29
 */

namespace AppBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class RegistrationsConfirmListener implements EventSubscriberInterface
{
    private $router;
    private $session;

    public function __construct(UrlGeneratorInterface $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }


    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_CONFIRM => 'onRegistrationConfirm'
        );

    }

    public function onRegistrationConfirm(GetResponseUserEvent $event)
    {
        $url = $this->router->generate('fos_user_profile_edit');
        $this->session->getFlashBag()->add('success', "Your account is now activated.");
        $event->setResponse(new RedirectResponse($url));
    }
}