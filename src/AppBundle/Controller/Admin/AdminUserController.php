<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 08/05/16
 * Time: 18:15
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminUserController
 * @package AppBundle\Controller\Admin
 */
class AdminUserController extends Controller
{
    /**
     * @return Response
     */
    public function indexUsersAction(): Response
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $users = $entityManager->getRepository('AppBundle:User')->findAll();

        return $this->render(
            'AppBundle:Admin:users.html.twig',
            [
                'users' => $users
            ]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function enableUserAjaxAction(Request $request): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $data = $request->request->get('data');
        $name = $data[0];
        $id = $data[1];
        $isEnabled = $data[2] === "true" ? true : false;

        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $entityManager->getRepository('AppBundle:User')->findOneById($id);

        if (!$user) {
            return new JsonResponse(array('message' => 'Can\'t change'), 400);
        }

        if ('enable' === $name) {
            $user->setEnabled($isEnabled);
        } else {
            $user->setNotifications($isEnabled);
        }

        $entityManager->flush($user);

        return new JsonResponse(array('message' => 'Success!'), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteUserAjaxAction(Request $request): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $id = $request->request->get('id');

        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $entityManager->getRepository('AppBundle:User')->find($id);

        if (!$user) {
            return new JsonResponse(array('message' => 'User not found'), 400);
        }

        // Whipe out all user requests
        $qbDeleteRequests = $entityManager->createQueryBuilder();
        $qbDeleteRequests->delete('AppBundle:Request', 'r');
        $qbDeleteRequests->where('r.userId = :user');
        $qbDeleteRequests->setParameter('user', $user->getId());
        $qbDeleteRequests->getQuery()->execute();

        // Remove user itself
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse(array('message' => 'Success!'), 200);
    }
}
