<?php

namespace App\Controller;

use App\Entity\CompanyData;
use App\Entity\Files;
use App\Form\FilesType;
use App\Repository\CompanyDataRepository;
use App\Repository\FilesRepository;
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

class DashboardController extends AbstractController
{
    private $session;
    private $urlGenerator;

    public function __construct(SessionInterface $session,UrlGeneratorInterface $urlGenerator)
    {
        $this->session = $session;
        $this->urlGenerator =$urlGenerator;
    }

    /**
     * @Route("/dashboard/company", name="dashboard_company", methods={"GET"})
     */
    public function companyIndex():Response
    {
        return $this->render('dashboard/company.html.twig', [
            'company' => $this->session->get("company"),
            ]
        );
    }


    /**
     * @Route("/dashboard/clear/session/{type}", name="dashboard_clear", methods={"GET"})
     */
    public function clearSession($type):Response
    {
        if($type != "all") {
            $this->session->remove($type);
        }else{
            $this->session->clear();
        }
        return new RedirectResponse($this->urlGenerator->generate('app_main'));
    }


}
