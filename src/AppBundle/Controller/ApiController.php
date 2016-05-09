<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 07/05/16
 * Time: 21:34
 */

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiController
 * @package AppBundle\Controller
 */
class ApiController extends Controller
{
    /**
     * @param string $apiKey
     * @return Response
     */
    public function fetchForecastJsonAction(string $apiKey): Response
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        // Check apiKey (user should be enabled)
        $validApi = $entityManager->getRepository('AppBundle:User')->findOneBy(['api' => $apiKey, 'enabled' => 1]);
        if (!$validApi) {
            // API key is not found. Return error message
            $forecast = [];
            $messageArray = ['Status' => 'Error', 'Text' => 'API key is expired and/or not valid'];
        } else {
            // Fetch forecast data
            $forecast = $entityManager->getRepository('AppBundle:Forecast')->getOurTodayForecast();
            $messageArray = ['Status' => 'OK', 'Text' => 'Thank you for being our customer'];
        }
        array_push($forecast, $messageArray);

        return new JsonResponse($forecast);
    }
}
