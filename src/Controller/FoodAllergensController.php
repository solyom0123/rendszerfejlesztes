<?php

namespace App\Controller;

use App\Entity\FoodAllergens;
use App\Form\FoodAllergensType;
use App\Repository\FoodAllergensRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/food/allergens")
 */
class FoodAllergensController extends AbstractController
{
    /**
     * @Route("/", name="food_allergens_index", methods={"GET"})
     */
    public function index(FoodAllergensRepository $foodAllergensRepository): Response
    {
        return $this->render('food_allergens/index.html.twig', [
            'food_allergens' => $foodAllergensRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="food_allergens_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $foodAllergen = new FoodAllergens();
        $form = $this->createForm(FoodAllergensType::class, $foodAllergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($foodAllergen);
            $entityManager->flush();

            return $this->redirectToRoute('food_allergens_index');
        }

        return $this->render('food_allergens/new.html.twig', [
            'food_allergen' => $foodAllergen,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="food_allergens_show", methods={"GET"})
     */
    public function show(FoodAllergens $foodAllergen): Response
    {
        return $this->render('food_allergens/show.html.twig', [
            'food_allergen' => $foodAllergen,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="food_allergens_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FoodAllergens $foodAllergen): Response
    {
        $form = $this->createForm(FoodAllergensType::class, $foodAllergen);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('food_allergens_index');
        }

        return $this->render('food_allergens/edit.html.twig', [
            'food_allergen' => $foodAllergen,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="food_allergens_delete", methods={"DELETE"})
     */
    public function delete(Request $request, FoodAllergens $foodAllergen): Response
    {
        if ($this->isCsrfTokenValid('delete'.$foodAllergen->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($foodAllergen);
            $entityManager->flush();
        }

        return $this->redirectToRoute('food_allergens_index');
    }
}
