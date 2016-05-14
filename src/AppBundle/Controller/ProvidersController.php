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
        $dateTo = date('Y-m-d');
        $dateFrom = date('Y-m-d', strtotime($dateTo.'-7 day'));

        $em = $this->getDoctrine()->getManager();
        $providers = $em->getRepository('AppBundle:Forecast')->getAverageProvidersAccuracy($dateFrom, $dateTo);

        return $this->render('AppBundle:Forecast:providers_main.html.twig', array(
            'providers'  => $providers
        ));
    }
    /**
     * Returns temperatures. Example: '12,15,17,18,18,'
     * @param $temperatures
     * @param $dates
     * @return string
     */
    private function renderData($temperatures, $dates)
    {
        $data = '';
        $numTemp = count($temperatures);
        foreach ($dates as $item) {
            $found = false;
            for ($i = 0; $i < $numTemp; $i++) {
                if ($item['tdate'] == $temperatures[$i]['tdate']) {
                    $found = true;
                    $data .= $temperatures[$i]['avg_temp'].',';
                    break;
                }
            }
            if (false === $found) {
                $data .= ',';
            }
        }
        return $data;
    }

    /**
     * Returns labels. Example: '"2016-04-02","2016-04-03", ... "2016-05-01,"'
     * @param $dates
     * @return string
     */
    private function renderLabels($dates)
    {
        $labels = '';
        foreach ($dates as $item) {
            $labels .= '"'.$item['tdate'].'",';
        }

        return $labels;
    }
}
