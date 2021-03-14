<?php

namespace App\Controller;

use App\Entity\FoodImages;
use App\Form\FoodImagesType;
use App\Repository\FoodImagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/food/images")
 */
class FoodImagesController extends AbstractController
{
    /**
     * @Route("/", name="food_images_index", methods={"GET"})
     */
    public function index(FoodImagesRepository $foodImagesRepository): Response
    {
        return $this->render('food_images/index.html.twig', [
            'food_images' => $foodImagesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="food_images_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $foodImage = new FoodImages();
        $form = $this->createForm(FoodImagesType::class, $foodImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($foodImage);
            $entityManager->flush();

            return $this->redirectToRoute('food_images_index');
        }

        return $this->render('food_images/new.html.twig', [
            'food_image' => $foodImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="food_images_show", methods={"GET"})
     */
    public function show(FoodImages $foodImage): Response
    {
        return $this->render('food_images/show.html.twig', [
            'food_image' => $foodImage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="food_images_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FoodImages $foodImage): Response
    {
        $form = $this->createForm(FoodImagesType::class, $foodImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('food_images_index');
        }

        return $this->render('food_images/edit.html.twig', [
            'food_image' => $foodImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="food_images_delete", methods={"DELETE"})
     */
    public function delete(Request $request, FoodImages $foodImage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$foodImage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($foodImage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('food_images_index');
    }
}
