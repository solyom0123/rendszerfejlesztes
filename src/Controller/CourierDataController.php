<?php

namespace App\Controller;

use App\Entity\CourierData;
use App\Entity\Notification;
use App\Entity\Suborder;
use App\Enums\OrderStatus;
use App\Form\CourierDataType;
use App\Repository\CourierDataRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/courier/data")
 */
class CourierDataController extends AbstractController
{
    /**
     * @Route("/", name="courier_data_index", methods={"GET"})
     */
    public function index(CourierDataRepository $courierDataRepository, Security $security): Response
    {
        return $this->render('courier_data/index.html.twig', [
            'courier_datas' => $courierDataRepository->findByUser($security->getUser()),
        ]);
    }

    /**
     * @Route("/new", name="courier_data_new", methods={"GET", "POST"})
     */
    public function new(Request $request, Security $security): Response
    {
        $courierDatum = new CourierData();
        $form = $this->createForm(CourierDataType::class, $courierDatum);
        $form->handleRequest($request);
        $courierDatum->setUser($security->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($courierDatum);
            $entityManager->flush();

            return $this->redirectToRoute('courier_data_index');
        }

        return $this->render('courier_data/new.html.twig', [
            'courier_datum' => $courierDatum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="courier_data_show", methods={"GET"})
     */
    public function show(CourierData $courierDatum): Response
    {
        return $this->render('courier_data/show.html.twig', [
            'courier_datum' => $courierDatum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="courier_data_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id, UserRepository $userRepository, CourierDataRepository $courierDataRepository): Response
    {
        $user = $userRepository->find($id);
        $courierDatum = $courierDataRepository->findByUser($user);
        if (sizeof($courierDatum) > 0) {
            $courierDatum = $courierDatum[0];
        } else {
            return $this->redirectToRoute('dashboard_courier');
        }
        $form = $this->createForm(CourierDataType::class, $courierDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('courier_data_index');
        }

        return $this->render('courier_data/edit.html.twig', [
            'courier_datum' => $courierDatum,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/jobs/assigned", name="courier_assigned_jobs", methods={"GET", "POST"})
     */
    public function showAssignedJobs(SessionInterface $session, OrderRepository $or, CourierDataRepository $cdr, UserRepository $ur)
    {
        $courierId = null;

        if (!$session->has('courier')) {
            return $this->redirectToRoute('index');
        }

        $courierId = $session->get('courier');

        $user = $ur->findOneBy(['id' => $courierId]);

        if (!$user) {
            return $this->redirectToRoute('app_main');
        }

        $courier = $cdr->findOneBy(['user' => $user]);

        if (!$courier) {
            return $this->redirectToRoute('app_main');
        }

        if (!$courier->getUser()) {
            return $this->redirectToRoute('app_main');
        }

        $suborder = $or->findAssignedOrders($courier->getUser());

        foreach ($suborder as $sub)
            foreach ($sub->getFoods() as $food) {
                $count = $or->countByFoodAndSuborder($food->getId(), $sub->getId());
                if ($count > 1) {
                    for ($i = 0; $i < $count - 1; $i++) {
                        $sub->addFood($food);
                    }
                }
            }

        return $this->render('dashboard/courier_assigned_jobs.html.twig', [
            'suborder' => $suborder
        ]);
    }

    /**
     * @Route("/order/set-delivery-status/{so}/{type}", name="courier_set_delivery_status", methods={"GET", "POST"})
     */
    public function setDeliveryStatus(SessionInterface $session,?Suborder $so = null, ?string $type = null)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$so) {
            return $this->redirectToRoute('app_main');
        }

        switch ($type) {
            case 'cancel':
                $so->setStatus(OrderStatus::$DELIVERY_STATUS_CANCELLED);

                $notification = new Notification();

                $notification->setRestaurant($so->getRestaurant());

                $courier = $session->get('courier');

                $link = "<a href='".$this->generateUrl('order_edit',['id'=>$so->getId()])."'>".$so->getId()."</a>";

                $message = $link." számú rendelését ".$courier." azonosítójú futár visszautasította.";

                $notification->setMessage($message);

                $notification->setSeen(false);

                $em->persist($notification);
                $em->flush();
                break;
            case 'start':
                $so->setStatus(OrderStatus::$DELIVERY_STATUS_IN_PROGRESS);
                break;
            case 'finish':
                $so->setStatus(OrderStatus::$DELIVERY_STATUS_FINISHED);
                break;
            case 'failed':
                $so->setStatus(OrderStatus::$DELIVERY_STATUS_FAILED);
                break;
            default:
                return $this->redirectToRoute('app_main');
        }

        $em->persist($so);

        $em->flush();

        return $this->redirectToRoute('courier_assigned_jobs');
    }

    /**
     * @Route("/order/take-job/{so}", name="courier_take_job", methods={"GET", "POST"})
     */
    public function takeJob(SessionInterface $session, CourierDataRepository $cdr, UserRepository $ur, ?Suborder $so = null)
    {
        if (!$so) {
            return $this->redirectToRoute('app_main');
        }

        $courierId = null;

        if (!$session->has('courier')) {
            return $this->redirectToRoute('app_main');
        }

        $courierId = $session->get('courier');

        if (!$courierId) {
            return $this->redirectToRoute('app_main');
        }

        $user = $ur->findOneBy(['id' => $courierId]);

        if (!$user) {
            return $this->redirectToRoute('app_main');
        }

        $courier = $cdr->findOneBy(['user' => $user]);

        if (!$courier) {
            return $this->redirectToRoute('app_main');
        }

        $courierUser = $courier->getUser();

        if (!$courierUser) {
            return $this->redirectToRoute('app_main');
        }

        $so->setCourier($courierUser);
        $so->setStatus(OrderStatus::$DELIVERY_STATUS_ASSIGNED);

        $this->getDoctrine()->getManager()->persist($so);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('courier_available_jobs');
    }

    /**
     * @Route("/jobs/available", name="courier_available_jobs", methods={"GET", "POST"})
     */
    public function showAvailableJobs(SessionInterface $session, OrderRepository $or, CourierDataRepository $cdr, UserRepository $ur)
    {
        $courierId = null;

        if (!$session->has('courier')) {
            return $this->redirectToRoute('index');
        }

        $courierId = $session->get('courier');

        $user = $ur->findOneBy(['id' => $courierId]);

        if (!$user) {
            return $this->redirectToRoute('app_main');
        }

        $courier = $cdr->findOneBy(['user' => $user]);

        if (!$courier) {
            return $this->redirectToRoute('app_main');
        }

        $suborder = $or->findAvailableOrders($courier);

        foreach ($suborder as $sub)
            foreach ($sub->getFoods() as $food) {
                $count = $or->countByFoodAndSuborder($food->getId(), $sub->getId());
                if ($count > 1) {
                    for ($i = 0; $i < $count - 1; $i++) {
                        $sub->addFood($food);
                    }
                }
            }

        return $this->render('dashboard/courier_available_jobs.html.twig', [
            'suborder' => $suborder
        ]);

    }

    /**
     * @Route("/{id}", name="courier_data_delete", methods={"POST"})
     */
    public function delete(Request $request, CourierData $courierDatum): Response
    {
        if ($this->isCsrfTokenValid('delete' . $courierDatum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($courierDatum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('courier_data_index');
    }
}
