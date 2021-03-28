<?php

namespace App\Controller;

use App\Entity\CourierData;
use App\Form\CourierDataType;
use App\Repository\CourierDataRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function index(CourierDataRepository $courierDataRepository,Security $security): Response
    {
        return $this->render('courier_data/index.html.twig', [
            'courier_datas' => $courierDataRepository->findByUser($security->getUser()),
        ]);
    }

    /**
    * @Route("/new", name="courier_data_new", methods={"GET", "POST"})
     */
    public function new(Request $request,Security $security): Response
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
    public function edit(Request $request,$id,UserRepository $userRepository,CourierDataRepository $courierDataRepository): Response
    {
        $user=$userRepository->find($id);
        $courierDatum=$courierDataRepository->findByUser($user);
        if(sizeof($courierDatum)>0){
            $courierDatum=$courierDatum[0];
        }else{
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
    * @Route("/{id}", name="courier_data_delete", methods={"POST"})
     */
    public function delete(Request $request, CourierData $courierDatum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$courierDatum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($courierDatum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('courier_data_index');
    }
}
