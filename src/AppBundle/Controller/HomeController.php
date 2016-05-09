<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 * @package AppBundle\Controller
 */
class HomeController extends Controller
{
    /**
     * Home page index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(): Response
    {
        return $this->render('AppBundle:Home:index.html.twig', array(
            // ...
        ));
    }
}
