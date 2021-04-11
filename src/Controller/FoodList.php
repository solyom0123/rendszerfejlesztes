<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/food_list")
 */
class FoodList extends AbstractController
{
    /**
     * @Route("/", name="restaurant_index", methods={"GET"})
     */
    public function index(RestaurantRepository $restaurantRepository): Response
    {

            return $this->render('food_list/index.html.twig', [
                'restaurants' => $restaurantRepository->findAll(),
            ]);
     }

    /**
     * @Route("/new", name="food_list_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);
        $restaurant->setOwner($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($restaurant);
            $entityManager->flush();

            return $this->redirectToRoute('app_main');
        }

        return $this->render('food_list/new.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="food_list_show", methods={"GET"})
     */
    public function show(Restaurant $restaurant): Response
    {
        return $this->render('food_list/show.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="food_list_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Restaurant $restaurant): Response
    {
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_main');
        }

        return $this->render('food_list/edit.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="food_list_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Restaurant $restaurant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($restaurant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_clear',["type"=>"company"]);
    }
}
