<?php

namespace App\Form;

use App\Entity\CourierData;
use App\Entity\Restaurant;
use App\Entity\Suborder;
use App\Entity\User;
use App\Enums\OrderStatus;
use App\Repository\CourierDataRepository;
use App\Repository\RestaurantRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuborderType extends AbstractType
{
    private $session;
    private $restaurantRepository;
    private CourierDataRepository $cdr;

    public function __construct(SessionInterface $session, RestaurantRepository $restaurantRepository, CourierDataRepository $cdr)
    {
        $this->session = $session;
        $this->restaurantRepository = $restaurantRepository;
        $this->cdr = $cdr;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('courier', EntityType::class, array(
                'class' => User::class,
                'multiple' => false,
                'required' => false,
                'empty_data' => "",
                'choice_label' => function (User $user) {
                    $courier = $this->cdr->findOneBy(['user' => $user]);

                    return $courier->getName() . " (azonosító: " . $courier->getId() . "; jármű: " . $courier->getVehicleType() . ")";
                },
                'query_builder' => function (UserRepository $repository) use ($builder) {
                    $qb = $repository->createQueryBuilder('u');
                    /* @var $restaurant Restaurant */
                    $entity = $builder->getData();
                    $restaurant = $this->restaurantRepository->find($this->session->get("company"));
                    $date = $entity->getParentOrder()->getDate();
                    $day = $date->format('w');
                    $qb->leftJoin(
                        'App\Entity\CourierData',
                        'c',
                        \Doctrine\ORM\Query\Expr\Join::WITH,
                        'u = c.user'
                    );

                    switch ($day + 1) {
                        case "1":
                        {
                            $qb->andWhere('c.fromWorkingDateMonday <= ?1 and ?2 >= c.toWorkingDateMonday');
                            break;
                        }
                        case "2":
                        {
                            $qb->andWhere('c.fromWorkingDateTuesday <= ?1 and ?2 >= c.toWorkingDateTuesday');
                            break;
                        }
                        case "3":
                        {
                            $qb->andWhere('c.fromWorkingDateWednesday <= ?1 and ?2 >= c.toWorkingDateWednesday');
                            break;
                        }
                        case "4":
                        {
                            $qb->andWhere('c.fromWorkingDateThursday <= ?1 and ?2 >= c.toWorkingDateThursday');
                            break;
                        }
                        case "5":
                        {
                            $qb->andWhere('c.fromWorkingDateFriday <= ?1 and ?2 >= c.toWorkingDateFriday');
                            break;
                        }
                        case "6":
                        {
                            $qb->andWhere('c.fromWorkingDateSaturday <= ?1 and ?2 >= c.toWorkingDateSaturday');
                            break;
                        }
                        case "7":
                        {
                            $qb->andWhere(' c.fromWorkingDateSunday <= ?1 and ?2 >= c.toWorkingDateSunday');
                            break;
                        }
                    };

                    $qb->andWhere('LOWER(c.location) LIKE LOWER(?3)');

                    $qb->setParameter('1', $date->format('H:i:s'))
                        ->setParameter('2', '23:59:59')
                        ->setParameter('3', '%' . $restaurant->getAddress() . '%')
                        ->orderBy('c.id', 'ASC');

                    return $qb;
                }))
            ->add('waitingTime')
            ->add('status', ChoiceType::class, [
                'multiple' => false,
                'choices' => [
                    'ordered' => OrderStatus::$ORDERED,
                    'accepted' => OrderStatus::$ACCEPTED,
                    'in_progress' => OrderStatus::$IN_PROGRESS,
                    'done' => OrderStatus::$DONE
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Suborder::class,
        ]);
    }
}
