<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 09/05/16
 * Time: 13:05
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminApiController
 * @package AppBundle\Controller\Admin
 */
class AdminApiController extends Controller
{
    /**
     * @return Response
     */
    public function indexApiAction(): Response
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $users = $entityManager->getRepository('AppBundle:User')->findAll();

        return $this->render(
            'AppBundle:Admin:api.html.twig',
            [
                'users' => $users
            ]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteApiAjaxAction(Request $request): JsonResponse
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

        $user->setApi(null);

        // Flush data
        $entityManager->flush();

        return new JsonResponse(array('message' => 'Success!'), 200);
    }
}
