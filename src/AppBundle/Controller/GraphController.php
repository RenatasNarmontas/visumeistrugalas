<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 12/05/16
 * Time: 04:20
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Temperature;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GraphController extends Controller
{
    public function graphDisplayAction(string $cityName): Response
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        $openWeatherMapTemp = $entityManager->getRepository('AppBundle:Temperature')
            ->getAvgTempDataForGraph($cityName, 'OpenWeatherMap');
        $yahooMapTemp = $entityManager->getRepository('AppBundle:Temperature')
            ->getAvgTempDataForGraph($cityName, 'Yahoo');
        $wundergroundTemp = $entityManager->getRepository('AppBundle:Temperature')
            ->getAvgTempDataForGraph($cityName, 'Weather Underground');

        $dates = $entityManager->getRepository('AppBundle:Temperature')->getUniqueDateForGraph($cityName);

        return $this->render('@App/History/graph.html.twig', array(
            'city' => $cityName,
            'labels' => $this->renderLabels($dates),
            'openweathermapData' => $this->renderData($openWeatherMapTemp, $dates),
            'yahooData' => $this->renderData($yahooMapTemp, $dates),
            'wundergroundData' => $this->renderData($wundergroundTemp, $dates),
        ));
    }

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
     * Returns labels. Example: 'labels: ["2016-04-02","2016-04-03", ... "2016-05-01"]'
     * @param $dates
     * @return string
     */
    private function renderLabels($dates)
    {
        // render labels
        $labels = '';
        $numItems = count($dates);
        $i = 0;
        foreach ($dates as $item) {
            if (++$i !== $numItems) {
                // last item
                $labels .= '"'.$item['tdate'].'",';
            } else {
                $labels .= '"'.$item['tdate'].'"';
            }
        }

        return $labels;
    }
}
