<?php

namespace App\Controller;

use App\Entity\FoodAllergens;
use App\Form\FoodAllergensType;
use App\Repository\FoodAllergensRepository;
use App\Repository\FoodRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/foodallergens")
 */
class FoodAllergensController extends AbstractController
{
    /**
     * @Route("/", name="food_allergens_index", methods={"GET"})
     */
    public function index(FoodAllergensRepository $foodAllergensRepository,SessionInterface $session,RestaurantRepository $restaurantRepository): Response
    {
        $restaurant = $restaurantRepository->find($session->get("company"));
        return $this->render('food_allergens/index.html.twig', [
            'food_allergens' => $foodAllergensRepository->findByRestaurant($restaurant),
        ]);
    }

    /**
     * @Route("/new", name="food_allergens_new", methods={"GET","POST"})
     */
    public function new(Request $request,SessionInterface $session,RestaurantRepository $restaurantRepository,FoodRepository $foodRepository): Response
    {
        $restaurant = $restaurantRepository->find($session->get("company"));
        $foodAllergen = new FoodAllergens();
        $form = $this->createForm(FoodAllergensType::class, $foodAllergen);
        $form->handleRequest($request);
        $foodAllergen->setRestaurant($restaurant);

        if ($form->isSubmitted() && $form->isValid()) {
            for($i=0;$i<sizeof($foodAllergen->getFood());$i++){
                $food = $foodAllergen->getFood()->get($i);
                $food = $foodRepository->find($food->getId());
                $food->addFoodAllergen($foodAllergen);
            }
            $foods = $foodRepository->findByRestaurant($restaurant);
            for($i=0;$i<sizeof($foods);$i++){
                $food = $foods[$i];
                $food = $foodRepository->find($food->getId());
                if(!$this->isInArrayFood($food,$foodAllergen,$foodRepository)){
                    $food->removeFoodAllergen($foodAllergen);
                }
            }
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
    public function edit(Request $request, FoodAllergens $foodAllergen,FoodRepository $foodRepository,SessionInterface $session,RestaurantRepository $restaurantRepository): Response
    {
        $form = $this->createForm(FoodAllergensType::class, $foodAllergen);
        $form->handleRequest($request);
        $restaurant = $restaurantRepository->find($session->get('company'));
        if ($form->isSubmitted() && $form->isValid()) {
            for($i=0;$i<sizeof($foodAllergen->getFood());$i++){
                $food = $foodAllergen->getFood()->get($i);
                $food = $foodRepository->find($food->getId());
                $food->addFoodAllergen($foodAllergen);
            }
            $foods = $foodRepository->findByRestaurant($restaurant);
            for($i=0;$i<sizeof($foods);$i++){
                $food = $foods[$i];
                $food = $foodRepository->find($food->getId());
                if(!$this->isInArrayFood($food,$foodAllergen,$foodRepository)){
                    $food->removeFoodAllergen($foodAllergen);
                }
            }
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

    private function isInArrayFood($f, $t, $repo){
        $in = false;
        for($i=0; $i<sizeof($t->getFood()); $i++){
            $food = $t->getFood()->get($i);
            $food = $repo->find($food->getId());
            if($f == $food) {
                $in = true;
            }
        }
        return $in;
    }

}
