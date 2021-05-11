<?php

namespace App\Controller;

use App\Entity\Food;
use App\Entity\FoodAllergens;
use App\Entity\Menu;
use App\Form\FoodAllergensType;
use App\Repository\FoodAllergensRepository;
use App\Repository\FoodRepository;
use App\Repository\MenuRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function index(FoodRepository $foodRepository,SessionInterface $session,MenuRepository $menuRepository): Response
    {
        $shopList = $session->get("shopList");
        $response = new JsonResponse();
        $array = [];

        if($shopList) {
            foreach ($shopList as $item){
                if($item[1]=='f') {
                    $food = $foodRepository->find($item[0]);
                    $salePrice = $food->getPrice();
                    foreach ($food->getYes() as $sale){
                       if(new \DateTime() <= $sale->getEnd()){
                           $salePrice = $salePrice - ($food->getPrice() * $sale->getPercent()/100);
                       }
                    }
                    array_push($array, [
                        'id' => $item[0], 'name' => $food->getName(), 'price' => $salePrice, 'type'=>$item[1]
                    ]);
                }else{
                    $menu = $menuRepository->find($item[0]);

                    $salePrice = $this->calcSum($menu);
                    foreach ($menu->getYes() as $sale){
                        if(new \DateTime() <= $sale->getEnd()){
                            $salePrice = $salePrice - ($this->calcSum($menu) * $sale->getPercent()/100);
                        }
                    }
                    array_push($array, [
                        'id' => $item[0], 'name' => $menu->getName(), 'price' => $salePrice, 'type'=>$item[1]
                    ]);
                    /* @var $food Food */
                    foreach ($menu->getFoods() as $food){
                        array_push($array, [
                            'id' => $item[0], 'name' => $food->getName(), 'price' => '', 'type' => ''
                        ]);
                    }
                }
            }
        } else{
            $session->set("shopList",[]);
       }
        $response->setData(['data' => $array]);
        return $response;
    }
    private function calcSum($menu){
        $sum = 0;
        /* @var $food Food */
        foreach ($menu->getFoods() as $food){
            $sum = $sum+$food->getPrice();
        }
        return $sum;
    }
    /**
     * @Route("/{id}/add/{rest}/{type}", name="shop_list_add", methods={"GET","POST"})
     */
    public function new(int $id,SessionInterface $session,$rest,$type): Response
    {
       $shopList= $session-> get("shopList");
       $shopList[] = [$id,$type];
         $session->set("shopList",$shopList);
        return $this->redirectToRoute('food_list_index',array('id'=>$rest));
      }

    /**
     * @Route("/{id}/remove/{type}", name="shop_list_remove", methods={"GET","POST"})
     */
    public function remove(int $id,$type,SessionInterface $session): Response
    {
        $shopList= $session-> get("shopList");
        $shopList1 = array();
        $deleted= false;
        foreach ($shopList as $item) {
            if (($item[0] != $id || $item[1] != $type) || (($item[0] == $id && $item[1] == $type) && $deleted)) {
                $shopList1[] = $item;
            } else {
                if (!$deleted)
                    $deleted =true;
            }
        }
        $session->set("shopList",$shopList1);
        return $this->redirectToRoute('app_main');
    }

}
