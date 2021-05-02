<?php

namespace App\Form;

use App\Entity\Restaurant;
use App\Entity\Suborder;
use App\Repository\CourierDataRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuborderType extends AbstractType
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
            ->add('courier',EntityType::class,array(
                'class' => Food::class,
                'multiple' => true,
                'query_builder' => function (CourierDataRepository $repository) use($options){
                    $restaurant = $this->$repository->find($this->session->get("company"));
                    $dateTime = new \DateTime();
                    $qb = $repository->createQueryBuilder('c');
                    // the function returns a QueryBuilder object
                    return $qb
                        // find all users where 'deleted' is NOT '1'
                        ->where($qb->expr()->eq('c.', '?1'))
                        ->setParameter('1', $restaurant)
                        ->orderBy('c.id', 'ASC');
                }))
            ->add('waitingTime')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Suborder::class,
        ]);
    }
}
