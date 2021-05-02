<?php

namespace App\Controller;

use App\Entity\Food;
use App\Entity\Order;
use App\Entity\Restaurant;
use App\Entity\Suborder;
use App\Form\RestaurantType;
use App\Repository\FoodRepository;
use App\Repository\OrderRepository;
use App\Repository\RestaurantRepository;
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
        foreach ($suborder as $sub)
            foreach ($sub->getFoods() as $food) {
                $count = $orderRepository->countByFoodAndSuborder($food->getId(), $sub->getId());
                if ($count > 1) {
                    for ($i = 0; $i < $count - 1; $i++) {
                        $sub->addFood($food);
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
    public function finalizeOrder(Request $request, SessionInterface $session, FoodRepository $foodRepository)
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

            foreach ($shopList as $item) {
                $food = $foodRepository->findOneBy(['id' => $item]);
                if ($food->getRestaurant()->getId() == $restaurant_id) {
                    $orderArr[$restaurant_id]['foods'][] = $food;
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

                    if(count($shopList)>0) {
                 /**set main order**/
                $order->setCustomer($this->getUser());
                $order->setDate(new \DateTime());

                foreach ($shopList as $item) {
                    $food = $foodRepository->find($item);
                    $order->addFood($food);
                }
                $sum = 0;
                /* @var $item Food */
                foreach ($order->getFoods() as $item) {
                     $sum += $item->getPrice();
                }
                $order->setTotal($sum);
                /**end main order**/

                        /**start suborders**/

                foreach ($orderArr as $item) {
                    $suborder = new Suborder();

                    $suborder_data = $item['data'];

                    $sum = 0;
                    /* @var $food Food */
                    foreach ($item["foods"] as $food) {
                        $suborder->addFood($food);
                        $suborder->setRestaurant($food->getRestaurant());
                        $sum += $food->getPrice();
                    }
                    $suborder->setParentOrder($order);
                    $order->addSuborder($suborder);
                    $suborder->setTotalPrice($sum);
                    $suborder->setName($suborder_data['name']);
                    $suborder->setAddress($suborder_data['address']);
                    $suborder->setDeliveryMethod($suborder_data['delivery_mode']);
                    $suborder->setPaymentMethod($suborder_data['payment_mode']);
                    $suborder->setStatus("Összekészités");
                    $entityManager->persist($suborder);
                    $entityManager->flush();
                }
                $entityManager->persist($order);
                $entityManager->flush();
                $session->set("shopList", []);
            }
        return new JsonResponse(['url' => $this->generateUrl('app_main')]);
    }

    /**
     * @Route("/new", name="order_new", methods={"GET","POST"})
     */
    public function new(SessionInterface $session, FoodRepository $foodRepository, Security $security): Response
    {
        $shopList = $session->get("shopList");

        $formDataArr = [];

        if ($shopList) {

            foreach ($shopList as $sl) {

                $food = $foodRepository->findOneBy(['id' => $sl]);
                if (!$food) {
                    continue;
                }

                $restaurant = $food->getRestaurant();

                $elem = [
                    'food' => $food,
                    'amount' => array_count_values($shopList)[$sl],
                ];

                if (!isset($formDataArr[$restaurant->getName() . '_' . $restaurant->getId()])) {
                    $formDataArr[$restaurant->getName() . '_' . $restaurant->getId()] = [];
                    $formDataArr[$restaurant->getName() . '_' . $restaurant->getId()]['total'] = 0;
                }
                if (!in_array($elem, $formDataArr[$restaurant->getName() . '_' . $restaurant->getId()])) {
                    $formDataArr[$restaurant->getName() . '_' . $restaurant->getId()][] = $elem;
                }

                $formDataArr[$restaurant->getName() . '_' . $restaurant->getId()]['total'] += $food->getPrice();

            }

            //dd($formDataArr);


            return $this->render('dashboard/checkout.html.twig', [
                'data' => $formDataArr
            ]);


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
}
