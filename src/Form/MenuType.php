<?php

namespace App\Form;

use App\Entity\Food;
use App\Entity\Menu;
use App\Entity\MenuCategory;
use App\Entity\Restaurant;
use App\Repository\FoodRepository;
use App\Repository\MenuCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('menuCategory', EntityType::class, array(
                'class' => MenuCategory::class,
                'query_builder' => function (MenuCategoryRepository $repository) use ($options) {
                    $qb = $repository->createQueryBuilder('c');
                    $result = $qb
                        ->leftJoin('c.menus', 'm')
                        ->where('m.restaurant = :company')
                        ->orWhere('m is null')
                        ->setParameter('company', $options["company"])
                        ->orderBy('c.id', 'ASC');
                    //dd($result->getQuery()->getSQL());
                    //dd($result);
                    return $result;
                },
                'multiple' => true))
            ->add('foods', EntityType::class, array(
                'class' => Food::class,
                'query_builder' => function (FoodRepository $repository) use ($options) {
                    $qb = $repository->createQueryBuilder('c');
                    $result = $qb
                        ->where('c.restaurant = :company')
                        ->setParameter('company', $options["company"])
                        ->orderBy('c.id', 'ASC');
                    //dd($result->getQuery()->getSQL());
                    //dd($result);
                    return $result;
                },
                'multiple' => true))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
            'company' => Restaurant::class
        ]);
    }
}
