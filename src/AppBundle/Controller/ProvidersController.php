<?php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        if (!$city) {
            throw new NotFoundHttpException("There's no data about this city");
        }
        $temperatures = $em->getRepository('AppBundle:Temperature')
            ->findBy(array('city' => $city->getId(), 'date'=> new \DateTime('today')));

        return $this->render('AppBundle:Forecast:providers.html.twig', array(
            'city' => $city->getName(),
            'temperatures'  => $temperatures
        ));
    }

    /**
     * @return Response
     */
    public function showAction()
    {
        $now = date('Y-m-d');
        $date1 = strtotime($now);
        $date2 = strtotime("-7 day", $date1);
        $em = $this->getDoctrine()->getManager();
        $providers = $em->getRepository('AppBundle:Forecast')->getAverageProvidersAccuracy($date1, $date2);
        return $this->render('AppBundle:Forecast:providers_main.html.twig', array(
            'providers'  => $providers
        ));
    }
}
