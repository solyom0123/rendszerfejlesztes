<?php

namespace App\Controller;

use App\Entity\Food;
use App\Entity\Menu;
use App\Entity\Order;
use App\Entity\Restaurant;
use App\Entity\Suborder;
use App\Entity\User;
use App\Enums\OrderStatus;
use App\Form\RestaurantType;
use App\Form\SuborderType;
use App\Repository\FoodRepository;
use App\Repository\MenuRepository;
use App\Repository\OrderRepository;
use App\Repository\RestaurantRepository;
use App\Repository\SuborderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="order_index", methods={"GET"})
     */
    public function index(OrderRepository $orderRepository, RestaurantRepository $restaurantRepository, SessionInterface $session): Response
    {
        $restaurant = $restaurantRepository->find($session->get('company'));
        $suborder = $orderRepository->findByRestaurant($restaurant);
        foreach ($suborder as $sub) {
            foreach ($sub->getFoods() as $food) {
                $count = $orderRepository->countByFoodAndSuborder($food->getId(), $sub->getId());
                if ($count > 1) {
                    for ($i = 0; $i < $count - 1; $i++) {
                        $sub->addFood($food);
                    }
                }
            }
            foreach ($sub->getMenus() as $food) {
                $count = $orderRepository->countByMenuAndSuborder($food->getId(), $sub->getId());
                if ($count > 1) {
                    for ($i = 0; $i < $count - 1; $i++) {
                        $sub->addMenu($food);
                    }
                }
            }
        }
        return $this->render('suborder/index.html.twig', [
            'suborder' => $suborder,
        ]);

    }

    /**
     * @Route("/finalize", name="order_finalize", methods={"GET","POST"})
     */
    public function finalizeOrder(Request $request, SessionInterface $session, FoodRepository $foodRepository, MenuRepository $menuRepository)
    {
        $data = $request->request;

        $shopList = $session->get('shopList');

        $ordersNum = count($data) / 5;

        $entityManager = $this->getDoctrine()->getManager();

        $orderArr = [];

        for ($i = 0; $i < $ordersNum; $i++) {

            $restaurant_id = $data->get('restaurant_id_' . ($i + 1));
            $name = $data->get('name_' . ($i + 1));
            $delivery_mode = $data->get('delivery_mode_' . ($i + 1));
            $payment_mode = $data->get('payment_mode_' . ($i + 1));
            $address = $data->get('address_' . ($i + 1));
            $menu = null;
            $food = null;
            foreach ($shopList as $item) {
                if ($item[1] == 'f') {
                    $food = $foodRepository->findOneBy(['id' => $item[0]]);
                    if ($food->getRestaurant()->getId() == $restaurant_id) {
                        $orderArr[$restaurant_id]['foods'][] = $food;
                    }
                } else {
                    $menu = $menuRepository->findOneBy(['id' => $item[0]]);
                    if ($menu->getRestaurant()->getId() == $restaurant_id) {
                        $orderArr[$restaurant_id]['menus'][] = $menu;
                    }
                }
            }

            $orderArr[$restaurant_id]['data'] = [
                'name' => $name,
                'delivery_mode' => $delivery_mode,
                'payment_mode' => $payment_mode,
                'address' => $address
            ];

        }

        $order = new Order();

        if (count($shopList) > 0) {
            /**set main order**/
            $order->setCustomer($this->getUser());
            $order->setDate(new \DateTime());

            foreach ($shopList as $item) {
                if ($item[1] == 'f') {
                    $food = $foodRepository->find($item[0]);
                    $order->addFood($food);
                } else {
                    $menu = $menuRepository->find($item[0]);
                    $order->addMenu($menu);
                }
            }
            $sum = 0;
            /* @var $item Food */
            foreach ($order->getFoods() as $item) {
                $salePrice = $item->getPrice();
                foreach ($item->getYes() as $sale){
                    if(new \DateTime() <= $sale->getEnd()){
                        $salePrice = $salePrice - ($sale->getPercent()/100);
                    }
                }
                $sum += $salePrice;
            }
            foreach ($order->getMenus() as $item) {
                $salePrice = 0;
                foreach ($item->getFoods() as $f) {
                    $salePrice += $f->getPrice();
                }
                foreach ($item->getYes() as $sale){
                    if(new \DateTime() <= $sale->getEnd()){
                        $salePrice = $salePrice - ($sale->getPercent()/100);
                    }
                }
                $sum += $salePrice;
            }
            $order->setTotal($sum);
            /**end main order**/

            /**start suborders**/
            $suborders = array();
            foreach ($orderArr as $item) {
                $suborder = new Suborder();

                $suborder_data = $item['data'];

                $sum = 0;
                if (isset($item["foods"])) {
                    /* @var $food Food */
                    foreach ($item["foods"] as $food) {
                        $suborder->addFood($food);
                        $suborder->setRestaurant($food->getRestaurant());
                        $salePrice = $food->getPrice();
                        foreach ($food->getYes() as $sale){
                            if(new \DateTime() <= $sale->getEnd()){
                                $salePrice = $salePrice - ($food->getPrice() * ($sale->getPercent()/100));
                            }
                        }
                        $sum += $salePrice;
                    }
                }
                if (isset($item["menus"])) {
                    /* @var $menu Menu */
                    foreach ($item["menus"] as $menu) {
                        $suborder->addMenu($menu);
                        $suborder->setRestaurant($menu->getRestaurant());
                        $originPrice = 0;
                        foreach ($menu->getFoods() as $f) {
                            $originPrice += $f->getPrice();
                        }
                        $salePrice = $originPrice;
                        foreach ($menu->getYes() as $sale){
                            if(new \DateTime() <= $sale->getEnd()){
                                $salePrice = $salePrice - ($originPrice * $sale->getPercent()/100);
                            }
                        }
                        $sum += $salePrice;
                    }
                }
                $suborder->setParentOrder($order);
                $order->addSuborder($suborder);
                $suborder->setTotalPrice($sum);
                $suborder->setName($suborder_data['name']);
                $suborder->setAddress($suborder_data['address']);
                $suborder->setDeliveryMethod($suborder_data['delivery_mode']);
                $suborder->setPaymentMethod($suborder_data['payment_mode']);
                $suborder->setStatus(OrderStatus::$ORDERED);
                $suborders[] = $suborder;
                $entityManager->persist($suborder);
                $entityManager->flush();
            }
            /** end suborder */
            $entityManager->persist($order);
            $entityManager->flush();
            $session->set("shopList", []);
        }
        return new JsonResponse(['url' => $this->generateUrl('app_main')]);
    }

    /**
     * @Route("/new", name="order_new", methods={"GET","POST"})
     */
    public function new(SessionInterface $session, FoodRepository $foodRepository, MenuRepository $menuRepository, Security $security): Response
    {
        $shopList = $session->get("shopList");

        $formDataArr = [];

        if ($shopList) {
            foreach ($shopList as $sl) {
                 if ($sl[1] == 'f') {
                    $food = $foodRepository->findOneBy(['id' => $sl[0]]);
                } else {
                    $food = $menuRepository->find($sl[0]);

                }
                if (!$food) {
                    continue;
                }

                $restaurant = $food->getRestaurant();
                $subfoods = [];

                if ($sl[1] == 'm') {
                    /* @var $food Menu */
                    $subfoods = $food->getFoods();

                }
                $elem = [
                    'food' => $food,
                    'amount' => $this->arrayCount($shopList, $sl),
                    'type' => $sl[1],
                    'subfoods' => $subfoods
                ];

                if (!isset($formDataArr[$restaurant->getName() . '_' . $restaurant->getId()])) {
                    $formDataArr[$restaurant->getName() . '_' . $restaurant->getId()] = [];
                    $formDataArr[$restaurant->getName() . '_' . $restaurant->getId()]['total'] = 0;
                }
                if (!in_array($elem, $formDataArr[$restaurant->getName() . '_' . $restaurant->getId()])) {
                    $formDataArr[$restaurant->getName() . '_' . $restaurant->getId()][] = $elem;
                }
                if ($sl[1] == 'f') {
                    $salePrice = $food->getPrice();
                    foreach ($food->getYes() as $sale){
                        if(new \DateTime() <= $sale->getEnd()){
                            $salePrice = $salePrice - ($food->getPrice() * ($sale->getPercent()/100));
                        }
                    }
                    $formDataArr[$restaurant->getName() . '_' . $restaurant->getId()]['total'] += $salePrice;
                } else {

                    /* @var $food Menu */
                    $salePrice = $this->sum($food);
                    foreach ($food->getYes() as $sale){
                        if(new \DateTime() <= $sale->getEnd()){
                            $salePrice = $salePrice - ( $this->sum($food) * ($sale->getPercent()/100));
                        }
                    }
                    $formDataArr[$restaurant->getName() . '_' . $restaurant->getId()]['total'] += $salePrice;
                }

            }
            return $this->render('dashboard/checkout.html.twig', [
                'data' => $formDataArr
            ]);


        }
        return $this->redirectToRoute('app_main');

    }

    private function arrayCount($shoplist, $sl)
    {
        $count = 0;
        foreach ($shoplist as $item) {
            if ($item[0] == $sl[0] && $item[1] == $sl[1]) {
                $count = $count + 1;
            }
        }
        return $count;
    }

    private function sum(Menu $menu)
    {
        $count = 0;
        foreach ($menu->getFoods() as $item) {
            $count += $item->getPrice();
        }
        return $count;
    }

    /**
     * @Route("/{id}", name="order_show", methods={"GET"})
     */
    public function show(Restaurant $restaurant): Response
    {
        return $this->render('restaurant/show.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="order_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Suborder $suborder): Response
    {
        $form = $this->createForm(SuborderType::class, $suborder);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('order_index');
        }

        return $this->render('suborder/edit.html.twig', [
            'suborder' => $suborder,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="order_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Restaurant $restaurant): Response
    {
        if ($this->isCsrfTokenValid('delete' . $restaurant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($restaurant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dashboard_clear', ["type" => "company"]);
    }

    /**
     * @Route("/customer/customer-order", name="customer_order_show")
     */
    public function listOrdersCustomer(Request $request, SessionInterface $session)
    {
        $user = $this->getUser();
        $customer = $session->get('customer');

        if (!$user || !$customer) {
            throw new \Exception("Nincs felhasználó vagy vendég megadva!");
        }

        $em = $this->getDoctrine()->getManager();

        $orderRepository = $em->getRepository(Order::class);

        $suborder = $orderRepository->findByUser($user);

        foreach ($suborder as $sub) {
            foreach ($sub->getFoods() as $food) {
                $count = $orderRepository->countByFoodAndSuborder($food->getId(), $sub->getId());
                if ($count > 1) {
                    for ($i = 0; $i < $count - 1; $i++) {
                        $sub->addFood($food);
                    }
                }
            }
            foreach ($sub->getMenus() as $food) {
                $count = $orderRepository->countByMenuAndSuborder($food->getId(), $sub->getId());
                if ($count > 1) {
                    for ($i = 0; $i < $count - 1; $i++) {
                        $sub->addMenu($food);
                    }
                }
            }
        }
        return $this->render('dashboard/customer_orders.html.twig', [
            'suborder' => $suborder
        ]);
    }


    /**
     * @Route("/customer/customer-order/rating/{id}/{rating}", name="customer_rating")
     */
    public function userOrderRating(Suborder $suborder, $rating)
    {
        $suborder->setUserOrderRating($rating);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return new JsonResponse(['success'=>true]);
    }

    /**
     * @Route("/courier/set-order-jobs", name="courier_setorderjobs")
     */
    public function courierSetOrderJobs(Request $request, SuborderRepository $suborder): Response
    {
        $id = $request->request->get('id');
        $adat = $request->request->get('array1');
        $adat2 = $request->request->get('array2');
        $em = $this->getDoctrine()->getManager();
        /* for($i=0;$i<sizeof($adat);$i++) {
             $order=$suborder->findOneBy(['courier'=>$id,'displayorder'=>$adat[$i]]);
             if($order){
                 $order->setDisplayorder($adat2[$i]);

             }
         $em->persist($order);
         $em->flush();
         }*/
        $array = array();

        foreach ($adat2 as $k => $ad) {
            $order = $suborder->findOneBy(['courier' => $id, 'displayorder' => $ad]);

            if ($order) {
                $order->setDisplayorder($adat[$k]);

                $array[] = $order;
            }
        }

        foreach ($array as $a) {
            //$em->persist($a);

            $em->flush();
        }

        /*dump($array);
        dump($adat);
        dump($adat2);
        dump($adat[$k]);
        dd($adat2[$k]);*/

        return new JsonResponse(['url' => $this->generateUrl("courier_assigned_jobs")]);
    }

}
