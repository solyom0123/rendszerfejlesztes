<?php

namespace App\Form;

use App\Entity\Food;
use App\Entity\Menu;
use App\Entity\Sale;
use App\Repository\FoodRepository;
use App\Repository\MenuRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleType extends AbstractType
{
    private $session;
    private $restaurantRepository;
    public function __construct(SessionInterface $session,RestaurantRepository $restaurantRepository)
    {
        $this->session= $session;
        $this->restaurantRepository = $restaurantRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('start')
            ->add('end')
            ->add('percent')
            ->add('menus',EntityType::class,array(
                'class' => Menu::class,
                'required' => false,
                'multiple' => true,
                'query_builder' => function (MenuRepository $repository) use($options){
                    $restaurant = $this->restaurantRepository->find($this->session->get("company"));
                    $qb = $repository->createQueryBuilder('c');
                    // the function returns a QueryBuilder object
                    return $qb
                        // find all users where 'deleted' is NOT '1'
                        ->where($qb->expr()->eq('c.restaurant', '?1'))
                        ->setParameter('1', $restaurant)
                        ->orderBy('c.id', 'ASC');
                }))
            ->add('foods',EntityType::class,array(
                'class' => Food::class,
                'multiple' => true,
                    'required' => false,

                    'query_builder' => function (FoodRepository $repository) use($options){
                    $restaurant = $this->restaurantRepository->find($this->session->get("company"));
                    $qb = $repository->createQueryBuilder('c');
                    // the function returns a QueryBuilder object
                    return $qb
                        // find all users where 'deleted' is NOT '1'
                        ->where($qb->expr()->eq('c.restaurant', '?1'))
                        ->setParameter('1', $restaurant)
                        ->orderBy('c.id', 'ASC');
                })
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sale::class,
        ]);
    }
}
