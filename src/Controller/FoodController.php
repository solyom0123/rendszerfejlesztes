<?php

namespace App\Controller;

use App\Entity\Food;
use App\Entity\RestaurantCategory;
use App\Form\FoodType;
use App\Repository\FoodRepository;
use App\Repository\RestaurantRepository;
use MongoDB\Driver\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/food")
 */
class FoodController extends AbstractController
{
    /**
     * @Route("/", name="food_index", methods={"GET"})
     */
    public function index(FoodRepository $foodRepository,RestaurantRepository $restaurantRepository,SessionInterface $session): Response
    {
        $restaurantData = $restaurantRepository->find($session->get("company"));
        return $this->render('food/index.html.twig', [
            'food' => $foodRepository->findByRestaurant($restaurantData),
        ]);
    }

    /**
     * @Route("/new", name="food_new", methods={"GET","POST"})
     */
    public function new(Request $request,RestaurantRepository $restaurantRepository,SessionInterface $session): Response
    {
        $food = new Food();
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        $restaurantData = $restaurantRepository->find($session->get("company"));
        $food->setRestaurant($restaurantData);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($food);
            $entityManager->flush();
            return $this->redirectToRoute('food_index');
        }

        return $this->render('food/new.html.twig', [
            'food' => $food,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="food_show", methods={"GET"})
     */
    public function show(Food $food): Response
    {
        return $this->render('food/show.html.twig', [
            'food' => $food,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="food_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Food $food): Response
    {
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('food_index');
        }

        return $this->render('food/edit.html.twig', [
            'food' => $food,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="food_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Food $food): Response
    {
        if ($this->isCsrfTokenValid('delete'.$food->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($food);
            $entityManager->flush();
        }

        return $this->redirectToRoute('food_index');
    }
}
