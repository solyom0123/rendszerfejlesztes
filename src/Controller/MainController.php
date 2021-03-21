<?php

namespace App\Controller;

use App\Entity\CompanyData;
use App\Entity\Files;
use App\Enums\Roles;
use App\Form\FilesType;
use App\Repository\CompanyDataRepository;
use App\Repository\FilesRepository;
use App\Repository\RestaurantRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MainController extends AbstractController
{
    private $session;
    private $urlGenerator;

    public function __construct(SessionInterface $session,UrlGeneratorInterface $urlGenerator)
    {
        $this->session = $session;
        $this->urlGenerator =$urlGenerator;
    }

    /**
     * @Route("/", name="app_main", methods={"GET"})
     */
    public function index(Security $security, RestaurantRepository $restaurantRepository): Response
    {
        if (!$this->session->get("company") && !$this->session->get("customer") && !$this->session->get("courier")) {
            return $this->render('main/index.html.twig', [
                    'companies' => $restaurantRepository->findByOwner($security->getUser()),
                    ]
            );
        }else if($this->session->get("company")){
            return new RedirectResponse($this->urlGenerator->generate('dashboard_company'));
        }else if($this->session->get("customer")){
            return new RedirectResponse($this->urlGenerator->generate('dashboard_company'));
        }else if($this->session->get("courier")){
            return new RedirectResponse($this->urlGenerator->generate('dashboard_company'));
        }
    }


    /**
     * @Route("/main/save/session/company/{id}", name="app_main_set_session_company", methods={"GET"})
     */
    public function saveOwnerChooseInSession($id): Response
    {
        $this->session->set("company", $id);
        return new RedirectResponse($this->urlGenerator->generate('dashboard_company'));

    }

    /**
     * @Route("/main/save/session/customer/{id}", name="app_main_set_session_customer", methods={"GET"})
     */
    public function saveStudentChooseInSession($id): Response
    {
        $this->session->set("customer", $id);
        return new RedirectResponse($this->urlGenerator->generate('dashboard_customer'));

    }

    /**
     * @Route("/main/save/session/courier/{id}", name="app_main_set_session_courier", methods={"GET"})
     */
    public function saveCourierChooseInSession($id): Response
    {
        $this->session->set("courier", $id);
        return new RedirectResponse($this->urlGenerator->generate('dashboard_courier'));

    }

}
