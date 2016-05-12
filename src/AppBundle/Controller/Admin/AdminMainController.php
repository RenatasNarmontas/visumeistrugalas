<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 12/05/16
 * Time: 12:33
 */

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminMainController extends Controller
{
    /**
     * @return Response
     */
    public function indexAdminAction(): Response
    {
        return $this->render(
            'AppBundle:Admin:admin.html.twig'
        );
    }
}
