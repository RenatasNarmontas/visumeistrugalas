<?php
/**
 * Created by PhpStorm.
 * User: marina
 * Date: 16.4.27
 * Time: 16.56
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    public function indexAction(string $cityName, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $city = $em->getRepository('AppBundle:City')->findOneByName($cityName);
        if (!$city) {
            throw new NotFoundHttpException("There's no data about this city");
        }
        $forecasts = $em->getRepository('AppBundle:Forecast')->addDaysToForecastDate($city->getId());

        $request->attributes->set('cityName', $cityName);

        return $this->render('AppBundle:Forecast:cityForecast.html.twig', array(
            'city' => $city->getName(),
            'forecasts'  => $forecasts
        ));
    }
}
