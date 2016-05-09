<?php

namespace AppBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ProfileType;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class UserAccountController
 * @package AppBundle\Controller
 */
class UserAccountController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request): Response
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $dispatcher = $this->get('event_dispatcher');
        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);
        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }
        $form = $this->createForm(ProfileType::class, $user);
        $form->setData($user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);
            $userManager->updateUser($user);
            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_edit');

                $response = new RedirectResponse($url);
            }
            $dispatcher->dispatch(
                FOSUserEvents::PROFILE_EDIT_COMPLETED,
                new FilterUserResponseEvent($user, $request, $response)
            );
        }

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }
}
