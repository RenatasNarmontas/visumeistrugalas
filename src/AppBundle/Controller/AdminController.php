<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 01/05/16
 * Time: 19:15
 */

namespace AppBundle\Controller;


use AppBundle\Entity\City;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("/admin/cities", name="cities_manager")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function citiesManagerAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $cities = $entityManager->getRepository('AppBundle:City')->findAll();
        $requests = $entityManager->getRepository('AppBundle:Request')->findAll();

        $city = new City();
        $form = $this->createFormBuilder($city)
            ->add('name', TextType::class)
            ->add('country', TextType::class)
            ->add('countryIso3166', TextType::class, array('label' => 'Country ISO3166'))
            ->add('save', SubmitType::class, array('label' => 'Add City'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // perform data saving to DB
            $entityManager->persist($city);
            $entityManager->flush();
            //
            return $this->redirectToRoute('cities_manager');
        }

        return $this->render(
            'AppBundle:Admin:cities.html.twig',
            [
                'cities' => $cities,
                'requests' => $requests,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/delete_city/{id}", name="delete_city")
     */
    public function deleteCityAction(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $city = $entityManager->getRepository('AppBundle:City')->find($id);

        $entityManager->remove($city);
        $entityManager->flush();

        return $this->redirectToRoute('cities_manager');
    }
}