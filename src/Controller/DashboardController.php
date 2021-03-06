<?php

namespace App\Controller;

use App\Entity\CompanyData;
use App\Entity\Files;
use App\Form\FilesType;
use App\Repository\CompanyDataRepository;
use App\Repository\CourierDataRepository;
use App\Repository\FilesRepository;
use App\Repository\RestaurantRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DashboardController extends AbstractController
{
    private $session;
    private $urlGenerator;

    public function __construct(SessionInterface $session, UrlGeneratorInterface $urlGenerator)
    {
        $this->session = $session;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route("/dashboard/company", name="dashboard_company", methods={"GET"})
     */
    public function companyIndex(SessionInterface $session, RestaurantRepository $rr): Response
    {
        $companyId = $session->get('company');

        $restaurant = $rr->findOneBy(['id' => $companyId]);

        $notifs = $restaurant->getNotifications()->filter(function ($a){
            return $a->getSeen() == false;
        });

        $unreadNotifs = count($notifs);

        return $this->render('dashboard/company.html.twig', [
                'company' => $this->session->get("company"),
                'notifs' => $notifs,
                'unreadNotifs'=>$unreadNotifs
            ]
        );
    }

    /**
     * @Route("/dashboard/company/read-messages", name="dashboard_company_read_messages", methods={"GET"})
     */
    public function readMessages(SessionInterface $session, RestaurantRepository $rr){
        $companyId = $session->get('company');

        $restaurant = $rr->findOneBy(['id' => $companyId]);

        $notifs = $restaurant->getNotifications()->filter(function ($a){
            return $a->getSeen() == false;
        });

        $em = $this->getDoctrine()->getManager();

        foreach($notifs as $notif){
            $notif->setSeen(true);

            $em->persist($notif);

            $em->flush();
        }

        return new JsonResponse(['success'=>true]);
    }

    /**
     * @Route("/dashboard/customer", name="dashboard_customer", methods={"GET"})
     */
    public function customerIndex(): Response
    {
        return $this->render('dashboard/customer.html.twig', [
                'customer' => $this->session->get("customer"),
            ]
        );
    }

    /**
     * @Route("/dashboard/courier", name="dashboard_courier", methods={"GET"})
     */
    public function courierIndex(Security $security, CourierDataRepository $courierDataRepository): Response
    {

        $courier = $courierDataRepository->findByUser($security->getUser());
        if (sizeof($courier) > 0) {
            $courier = $courier[0];
        } else {
            $courier = null;
        }
        return $this->render('dashboard/courier.html.twig', [
                'customer' => $this->session->get("courier"),
                'courierData' => $courier
            ]
        );
    }

    /**
     * @Route("/dashboard/clear/session/{type}", name="dashboard_clear", methods={"GET"})
     */
    public function clearSession($type): Response
    {
        if ($type != "all") {
            $this->session->remove($type);
        }else{
            $this->session->remove('customer');
            $this->session->remove('company');
            $this->session->remove('courier');
        }
        return new RedirectResponse($this->urlGenerator->generate('app_main'));
    }


}
