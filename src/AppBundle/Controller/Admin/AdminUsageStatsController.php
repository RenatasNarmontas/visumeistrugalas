<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 09/05/16
 * Time: 12:34
 */

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class AdminUsageStatsController
 * @package AppBundle\Controller\Admin
 */
class AdminUsageStatsController extends Controller
{
    /**
     * @Route("/admin/stats", name="stats_manager")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
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
