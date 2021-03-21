<?php

namespace App\Controller;

use App\Entity\FoodImages;
use App\Form\FoodImagesType;
use App\Repository\FoodImagesRepository;
use App\Repository\FoodRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/foodimages")
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
    public function new(Request $request,SluggerInterface $slugger,SessionInterface $session,RestaurantRepository $restaurantRepository,FoodRepository $foodRepository): Response
    {
        $restaurant =$restaurantRepository->find($session->get('company'));
        $foodImage = new FoodImages();
        $form = $this->createForm(FoodImagesType::class, $foodImage,['company'=>$restaurant]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('file')->getData();

            for($i=0;$i<sizeof($foodImage->getFood());$i++){
                $food = $foodImage->getFood()->get($i);
                $food = $foodRepository->find($food->getId());
                $food->addFoodImage($foodImage);
            }
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $foodImage->setFilePath($newFilename);
            }

            // ... persist the $product variable or any other work
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
    public function edit(Request $request, FoodImages $foodImage,SessionInterface $session,RestaurantRepository $restaurantRepository,FoodRepository $foodRepository): Response
    {
        $restaurant =$restaurantRepository->find($session->get('company'));
        $form = $this->createForm(FoodImagesType::class, $foodImage,['company'=>$restaurant]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            for($i=0;$i<sizeof($foodImage->getFood());$i++){
                $food = $foodImage->getFood()->get($i);
                $food = $foodRepository->find($food->getId());
                $food->addFoodImage($foodImage);
            }
            $foods=$foodRepository->f
            for($i=0;$i<sizeof($teachers);$i++){
                $teacher = $teachers[$i];
                $teacher = $teacherRepository->find($teacher->getId());
                if(!$this->isInArray($teacher,$studyMaterial,$teacherRepository)){
                    $teacher->removeStudyMaterial($studyMaterial);
                }
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
        private function isInArray($fTeacher,$studyMaterial,$teacherRepository){
        $in = false;
        for($i=0;$i<sizeof($studyMaterial->getTeachers());$i++){
            $teacher = $studyMaterial->getTeachers()->get($i);
            $teacher = $teacherRepository->find($teacher->getId());
            if($fTeacher == $teacher) {
                $in = true;
            }
        }
        return $in;
    }
}
