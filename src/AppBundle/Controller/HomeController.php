<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 * @package AppBundle\Controller
 * @param Request $request
 * @return Response
 */
class HomeController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->setMethod('GET')
            ->add('city', SearchType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cityName = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $result = $em->getRepository('AppBundle:Forecast')
                ->getOurTomorrowTemperatureForCity(new \DateTime('now'), $cityName);
            return $this->render('AppBundle:Home:index.html.twig', array(
                'cities' => $result,
                'form' => $form->createView()
            ));
        }
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('AppBundle:Forecast')->getOurTomorrowTemperature(new \DateTime('now'));
        return $this->render('AppBundle:Home:index.html.twig', array(
            'cities'  => $result,
            'form' => $form->createView()
        ));
    }
}
