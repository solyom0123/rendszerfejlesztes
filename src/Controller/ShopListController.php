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
 * @Route("/shoplist")
 */
class ShopListController extends AbstractController
{
    /**
     * @Route("/", name="shop_list_index", methods={"GET"})
     */
    public function index(FoodAllergensRepository $foodAllergensRepository,SessionInterface $session,RestaurantRepository $restaurantRepository): Response
    {
        $shopList = $session->get("shopList");
       if($shopList) {
           return $this->redirectToRoute('food_allergens_index');
       } else{
          $session->set("shopList",[]);
       }
    }

    /**
     * @Route("/{id}/add", name="shop_list_add", methods={"GET","POST"})
     */
    public function new(int $id,SessionInterface $session): Response
    {
       $shopList= $session-> get("shopList");
       $shopList[] = $id;
       $session->set("shopList",$shopList);
        return $this->redirectToRoute('food_allergens_index');
      }

    /**
     * @Route("/{id}/remove", name="shop_list_remove", methods={"GET","POST"})
     */
    public function remove(int $id,SessionInterface $session): Response
    {
        $shopList= $session-> get("shopList");
        $shopList1 = array();
        $deleted= false;
        foreach ($shopList as $item){
            if($item != $id&&!$deleted){
                $shopList1[] =$item;
                $deleted=true;
            }
        }
        $session->set("shopList",$shopList1);
        return $this->redirectToRoute('app_main');
    }

}
