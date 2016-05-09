<?php
/**
 * Created by PhpStorm.
 * User: marina
 * Date: 16.4.27
 * Time: 16.56
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CityController extends Controller
{
    public function indexAction($cityName)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository('AppBundle:City')->findOneBy(array('name' => $cityName));
        $forecasts = $em->getRepository('AppBundle:Forecast')->addDaysToForecastDate($city->getId());
        return $this->render('AppBundle:Forecast:cityForecast.html.twig', array(
            'city' => $city->getName(),
            'forecasts'  => $forecasts
        ));

    }
}
