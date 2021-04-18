<?php

namespace App\Controller;

use App\Entity\FoodAllergens;
use App\Form\FoodAllergensType;
use App\Repository\FoodAllergensRepository;
use App\Repository\FoodRepository;
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
    public function index(FoodRepository $foodRepository,SessionInterface $session): Response
    {
        $shopList = $session->get("shopList");
        $response = new JsonResponse();
        $array = [];

        if($shopList) {
            foreach ($shopList as $item){
                $food = $foodRepository->find($item);
                array_push($array,[
                    'id'=>$item,'name'=>$food->getName(),'price'=>$food->getPrice()
                ]);

            }
        } else{
            $session->set("shopList",[]);
       }
        $response->setData(['data' => $array]);
        return $response;
    }

    /**
     * @Route("/{id}/add/{rest}", name="shop_list_add", methods={"GET","POST"})
     */
    public function new(int $id,SessionInterface $session,$rest): Response
    {
       $shopList= $session-> get("shopList");
       $shopList[] = $id;
         $session->set("shopList",$shopList);
        return $this->redirectToRoute('food_list_index',array('id'=>$rest));
      }

    /**
     * @Route("/{id}/remove/", name="shop_list_remove", methods={"GET","POST"})
     */
    public function remove(int $id,SessionInterface $session): Response
    {
        $shopList= $session-> get("shopList");
        $shopList1 = array();
        $deleted= false;
        foreach ($shopList as $item) {
            if ($item != $id || ($item == $id && $deleted)) {
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
