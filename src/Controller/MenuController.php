<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\MenuCategory;
use App\Form\MenuType;
use App\Repository\FoodRepository;
use App\Repository\MenuCategoryRepository;
use App\Repository\MenuRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/menu")
 */
class MenuController extends AbstractController
{
    /**
     * @Route("/", name="menu_index", methods={"GET"})
     */
    public function index(MenuRepository $menuRepository,RestaurantRepository $restaurantRepository,SessionInterface $session): Response
    {
        $restaurant = $restaurantRepository->find($session->get('company'));
        return $this->render('menu/index.html.twig', [
            'menus' => $menuRepository->findByRestaurant($restaurant),
        ]);
    }

    /**
     * @Route("/new", name="menu_new", methods={"GET","POST"})
     */
    public function new(Request $request,RestaurantRepository $restaurantRepository,SessionInterface $session,FoodRepository $foodRepository,MenuCategoryRepository $categoryRepository): Response
    {
        $restaurant = $restaurantRepository->find($session->get('company'));
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu,['company'=>$restaurant]);
        $form->handleRequest($request);
        $menu->setRestaurant($restaurant);
        if ($form->isSubmitted() && $form->isValid()) {
            for($i=0;$i<sizeof($menu->getFoods());$i++){
                $food = $menu->getFoods()->get($i);
                $food = $foodRepository->find($food->getId());
                $food->addMenu($menu);
            }
            $foods = $foodRepository->findByRestaurant($restaurant);
            for($i=0;$i<sizeof($foods);$i++){
                $food = $foods[$i];
                $food = $foodRepository->find($food->getId());
                if(!$this->isInArrayFood($food,$menu,$foodRepository)){
                    $food->removeMenu($menu);
                }
            }
            for($i=0;$i<sizeof($menu->getMenuCategory());$i++){
                $cat = $menu->getMenuCategory()->get($i);
                $cat = $categoryRepository->find($cat->getId());
                $cat->addMenu($menu);
            }
            $cats = $categoryRepository->findByRestaurant($restaurant);
            for($i=0;$i<sizeof($cats);$i++){
                $cat = $cats[$i];
                $cat = $categoryRepository->find($cat->getId());
                if(!$this->isInArrayMenuCategory($cat,$menu,$categoryRepository)){
                    $cat->removeMenu($menu);
                }
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($menu);
            $entityManager->flush();

            return $this->redirectToRoute('menu_index');
        }

        return $this->render('menu/new.html.twig', [
            'menu' => $menu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="menu_show", methods={"GET"})
     */
    public function show(Menu $menu): Response
    {
        return $this->render('menu/show.html.twig', [
            'menu' => $menu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="menu_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Menu $menu,RestaurantRepository $restaurantRepository,SessionInterface $session,FoodRepository $foodRepository,MenuCategoryRepository $categoryRepository): Response
    {
        $restaurant = $restaurantRepository->find($session->get('company'));
        $form = $this->createForm(MenuType::class, $menu,['company'=>$restaurant]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            for($i=0;$i<sizeof($menu->getFoods());$i++){
                $food = $menu->getFoods()->get($i);
                $food = $foodRepository->find($food->getId());
                $food->addMenu($menu);
            }
            $foods = $foodRepository->findByRestaurant($restaurant);
            for($i=0;$i<sizeof($foods);$i++){
                $food = $foods[$i];
                $food = $foodRepository->find($food->getId());
                if(!$this->isInArrayFood($food,$menu,$foodRepository)){
                    $food->removeMenu($menu);
                }
            }
            for($i=0;$i<sizeof($menu->getMenuCategory());$i++){
                $cat = $menu->getMenuCategory()->get($i);
                $cat = $categoryRepository->find($cat->getId());
                $cat->addMenu($menu);
            }
            $cats = $categoryRepository->findByRestaurant($restaurant);
            for($i=0;$i<sizeof($cats);$i++){
                $cat = $cats[$i];
                $cat = $categoryRepository->find($cat->getId());
                if(!$this->isInArrayMenuCategory($cat,$menu,$categoryRepository)){
                    $cat->removeMenu($menu);
                }
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('menu_index');
        }

        return $this->render('menu/edit.html.twig', [
            'menu' => $menu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="menu_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Menu $menu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menu->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($menu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('menu_index');
    }
    private function isInArrayFood($f, $t, $repo){
        $in = false;
        for($i=0; $i<sizeof($t->getFoods()); $i++){
            $food = $t->getFoods()->get($i);
            $food = $repo->find($food->getId());
            if($f == $food) {
                $in = true;
            }
        }
        return $in;
    }

    private function isInArrayMenuCategory($f, $t, $repo){
        $in = false;
        for($i=0; $i<sizeof($t->getMenuCategory()); $i++){
            $cat = $t->getMenuCategory()->get($i);
            $cat = $repo->find($cat->getId());
            if($f == $cat) {
                $in = true;
            }
        }
        return $in;
    }
}
