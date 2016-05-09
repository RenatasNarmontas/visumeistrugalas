<?php
/**
 * Created by PhpStorm.
 * User: marina
 * Date: 16.4.27
 * Time: 16.56
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CityController
 * @package AppBundle\Controller
 */
class CityController extends Controller
{
    /**
     * @param $cityName
     * @return Response
     */
    public function indexAction($cityName): Response
    {
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository('AppBundle:City')->findOneByName($cityName);
        $forecasts = $em->getRepository('AppBundle:Forecast')->addDaysToForecastDate($city->getId());

        return $this->render('AppBundle:Forecast:cityForecast.html.twig', array(
            'city' => $city->getName(),
            'forecasts'  => $forecasts
        ));
    }
}
