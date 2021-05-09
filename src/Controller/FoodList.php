<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\FoodRepository;
use App\Repository\MenuRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/foodlist")
 */
class FoodList extends AbstractController
{
    /**
     * @Route("/{id}", name="food_list_index", methods={"GET"})
     */
    public function index(FoodRepository $foodRepository,$id,RestaurantRepository $restaurantRepository,MenuRepository $menuRepository): Response
    {
            $restaurant=$restaurantRepository->find($id);
            return $this->render('food/index.html.twig', [
                'food' => $foodRepository->findByRestaurant($restaurant),'menu'=>$menuRepository->findByRestaurant($restaurant)
            ]);
     }
}
