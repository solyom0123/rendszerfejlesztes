<?php

namespace App\Controller;

use App\Entity\RestaurantCategory;
use App\Form\RestaurantCategoryType;
use App\Repository\RestaurantCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/restaurant/category')]
class RestaurantCategoryController extends AbstractController
{
    #[Route('/', name: 'restaurant_category_index', methods: ['GET'])]
    public function index(RestaurantCategoryRepository $restaurantCategoryRepository): Response
    {
        return $this->render('restaurant_category/index.html.twig', [
            'restaurant_categories' => $restaurantCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'restaurant_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $restaurantCategory = new RestaurantCategory();
        $form = $this->createForm(RestaurantCategoryType::class, $restaurantCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($restaurantCategory);
            $entityManager->flush();

            return $this->redirectToRoute('restaurant_category_index');
        }

        return $this->render('restaurant_category/new.html.twig', [
            'restaurant_category' => $restaurantCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'restaurant_category_show', methods: ['GET'])]
    public function show(RestaurantCategory $restaurantCategory): Response
    {
        return $this->render('restaurant_category/show.html.twig', [
            'restaurant_category' => $restaurantCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'restaurant_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RestaurantCategory $restaurantCategory): Response
    {
        $form = $this->createForm(RestaurantCategoryType::class, $restaurantCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('restaurant_category_index');
        }

        return $this->render('restaurant_category/edit.html.twig', [
            'restaurant_category' => $restaurantCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'restaurant_category_delete', methods: ['DELETE'])]
    public function delete(Request $request, RestaurantCategory $restaurantCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$restaurantCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($restaurantCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('restaurant_category_index');
    }
}
