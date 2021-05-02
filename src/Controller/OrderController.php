<?php

namespace App\Controller;

use App\Entity\Food;
use App\Entity\Order;
use App\Entity\Restaurant;
use App\Entity\Suborder;
use App\Entity\User;
use App\Form\RestaurantType;
use App\Repository\FoodRepository;
use App\Repository\OrderRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(OrderRepository $orderRepository,RestaurantRepository $restaurantRepository,SessionInterface $session): Response
    {
        $restaurant = $restaurantRepository->find($session->get('company'));
        $suborder = $orderRepository->findByRestaurant($restaurant);
        foreach ($suborder as $sub)
            foreach ($sub->getFoods() as $food) {
                $count =$orderRepository->countByFoodAndSuborder($food->getId(), $sub->getId());
                if ($count>1){
                    for ($i =0;$i <$count-1;$i++){
                        $sub->addFood($food);
                    }
                }
            }
        return $this->render('suborder/index.html.twig', [
            'suborder' => $suborder,
        ]);

    }

    /**
     * @Route("/new", name="order_new", methods={"GET","POST"})
     */
    public function new(SessionInterface $session, FoodRepository $foodRepository,Security $security): Response
    {
        $order = new Order();
        $shopList = $session->get("shopList");
        if ($shopList) {
            if(count($shopList)>0) {
                $order->setCustomer($security->getUser());
                $order->setDate(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                foreach ($shopList as $item) {
                    $food = $foodRepository->find($item);
                    $order->addFood($food);
                }
                $array = array();
                $sum = 0;
                /* @var $item Food */
                foreach ($order->getFoods() as $item) {
                    $array[$item->getRestaurant()->getId()] = array();
                    $sum += $item->getPrice();
                }
                $order->setTotal($sum);
                /* @var $item Food */
                foreach ($order->getFoods() as $item) {
                    $array[$item->getRestaurant()->getId()][] = $item;
                }
                foreach ($array as $item) {
                    $suborder = new Suborder();
                    $sum = 0;
                    /* @var $food Food */
                    foreach ($item as $food) {
                        $suborder->addFood($food);
                        $suborder->setRestaurant($food->getRestaurant());
                        $sum += $food->getPrice();
                    }
                    $suborder->setParentOrder($order);
                    $order->addSuborder($suborder);
                    $suborder->setTotalPrice($sum);
                    $suborder->setStatus("Összekészités");
                    $entityManager->persist($suborder);
                    $entityManager->flush();
                }
                $entityManager->persist($order);
                $entityManager->flush();
                $session->set("shopList", []);
            }
        }
        return $this->redirectToRoute('app_main');

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
    public function edit(Request $request, Restaurant $restaurant): Response
    {
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_main');
        }

        return $this->render('restaurant/edit.html.twig', [
            'restaurant' => $restaurant,
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
    public function listOrdersCustomer(Request $request, SessionInterface $session){
        $user = $this->getUser();
        $customer = $session->get('customer');

        if(!$user || !$customer){
            throw new \Exception("Nincs felhasználó vagy vendég megadva!");
        }

        $em = $this->getDoctrine()->getManager();

        $orderRepository = $em->getRepository(Order::class);

        $suborder = $orderRepository->findByUser($user);
        
        foreach ($suborder as $sub)
            foreach ($sub->getFoods() as $food) {
                $count =$orderRepository->countByFoodAndSuborder($food->getId(), $sub->getId());
                if ($count>1){
                    for ($i =0;$i <$count-1;$i++){
                        $sub->addFood($food);
                    }
                }
            }

        return $this->render('dashboard/customer_orders.html.twig',[
            'suborder'=>$suborder
        ]);
    }
}
