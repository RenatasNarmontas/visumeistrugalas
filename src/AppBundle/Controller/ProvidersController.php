<?php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProvidersController
 * @package AppBundle\Controller
 */
class ProvidersController extends Controller
{
    /**
     * @param $cityName
     * @return Response
     */
    public function indexAction($cityName)
    {

        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository('AppBundle:City')->findOneByName($cityName);
        $temperatures = $em->getRepository('AppBundle:Temperature')
            ->findBy(array('city' => $city->getId(), 'date'=> new \DateTime('today')));

        return $this->render('AppBundle:Forecast:providers.html.twig', array(
            'city' => $city->getName(),
            'temperatures'  => $temperatures
        ));
    }

    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();
        $providers = $em->getRepository('AppBundle:Forecast')->getAverageProvidersAccuracy('2016-05-11', '2016-05-12');
        return $this->render('AppBundle:Forecast:providers_main.html.twig', array(
            'providers'  => $providers
        ));

    }
}
