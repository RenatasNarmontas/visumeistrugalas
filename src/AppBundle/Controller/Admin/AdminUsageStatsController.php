<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 09/05/16
 * Time: 12:34
 */

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminUsageStatsController
 * @package AppBundle\Controller\Admin
 */
class AdminUsageStatsController extends Controller
{
    /**
     * @return Response
     */
    public function indexUsageStatsAction(): Response
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        /** @var User $user */
        $users = $entityManager->getRepository('AppBundle:User')->findAll();

        return $this->render(
            'AppBundle:Admin:stats.html.twig',
            [
                'users' => $users
            ]
        );
    }
}
