<?php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\DateTime;

class ProvidersController extends Controller
{
    public function indexAction($cityName)
    {

        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository('AppBundle:City')->findOneBy(array('name' => $cityName));
        $temperatures = $em->getRepository('AppBundle:Temperature')
                           ->findBy(array('city' => $city->getId(),'date'=> new \DateTime('today')));

        return $this->render('AppBundle:Forecast:providers.html.twig', array(
            'city' => $city->getName(),
            'temperatures'  => $temperatures
        ));
    }
}
