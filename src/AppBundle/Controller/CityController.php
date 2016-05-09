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
        $forecasts = $em->getRepository('AppBundle:Forecast')
            ->findBy(array('city' => $city->getId()));

        return $this->render('AppBundle:Forecast:cityForecast.html.twig', array(
            'city' => $city->getName(),
            'forecasts'  => $this->modifyForecastsArray($forecasts)
        ));

    }


    private function modifyForecastsArray(array $forecasts):array
    {
        $modifiedForecats = array();

        foreach ($forecasts as $forecast) {
            $date = date_format($forecast->getForecastDate(), 'Y-m-d');
            $forecastDate = date('Y-m-d', strtotime($date. ' +'. $forecast->getForecastDays() .'days'));
            $forecast->setForecastDate($forecastDate);
            $modifiedForecats[] = $forecast;
        }
        return $modifiedForecats;
    }
}
