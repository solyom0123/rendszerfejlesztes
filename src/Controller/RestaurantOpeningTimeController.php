<?php

namespace App\Controller;

use App\Entity\RestaurantOpeningTime;
use App\Form\RestaurantOpeningTimeType;
use App\Repository\RestaurantOpeningTimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/restaurant/opening/time")
*/
class RestaurantOpeningTimeController extends AbstractController
{
    /**
     * @Route("/", name="restaurant_opening_time_index", methods={"GET"})
    */
    public function index(RestaurantOpeningTimeRepository $restaurantOpeningTimeRepository): Response
    {
        return $this->render('restaurant_opening_time/index.html.twig', [
            'restaurant_opening_times' => $restaurantOpeningTimeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="restaurant_opening_time_new", methods={"GET", "POST"})
    */public function new(Request $request): Response
    {
        $restaurantOpeningTime = new RestaurantOpeningTime();
        $form = $this->createForm(RestaurantOpeningTimeType::class, $restaurantOpeningTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($restaurantOpeningTime);
            $entityManager->flush();

            return $this->redirectToRoute('restaurant_opening_time_index');
        }

        return $this->render('restaurant_opening_time/new.html.twig', [
            'restaurant_opening_time' => $restaurantOpeningTime,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="restaurant_opening_time_show", methods={"GET"})
    */public function show(RestaurantOpeningTime $restaurantOpeningTime): Response
    {
        return $this->render('restaurant_opening_time/show.html.twig', [
            'restaurant_opening_time' => $restaurantOpeningTime,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="restaurant_opening_time_edit", methods={"GET", "POST"})
    */public function edit(Request $request, RestaurantOpeningTime $restaurantOpeningTime): Response
    {
        $form = $this->createForm(RestaurantOpeningTimeType::class, $restaurantOpeningTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('restaurant_opening_time_index');
        }

        return $this->render('restaurant_opening_time/edit.html.twig', [
            'restaurant_opening_time' => $restaurantOpeningTime,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="restaurant_opening_time_delete", methods={"DELETE"})
    */public function delete(Request $request, RestaurantOpeningTime $restaurantOpeningTime): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurantOpeningTime->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($restaurantOpeningTime);
            $entityManager->flush();
        }

        return $this->redirectToRoute('restaurant_opening_time_index');
    }
}
