<?php

namespace App\Form;

use App\Entity\CourierData;
use App\Entity\Restaurant;
use App\Entity\Suborder;
use App\Enums\OrderStatus;
use App\Repository\CourierDataRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
                'class' => CourierData::class,
                'multiple' => false,
                'query_builder' => function (CourierDataRepository $repository) use($options){
                    $qb = $repository->createQueryBuilder('c');
                    /* @var $restaurant Restaurant*/
                    $restaurant = $this->restaurantRepository->find($this->session->get("company"));
                    $date = new \DateTime();
                    $day =$date->format('w');
                    $qb->where($qb->expr()->upper('?1').' like \'%'.$qb->expr()->upper('c.location').'%\'' );
                    switch ($day){
                        case "0":{
                            $qb->andWhere(' c.fromWorkingDateSunday >= ?2 and ?2 <= c.toWorkingDateSunday');
                            break;
                        }
                        case "1":{
                            $qb->andWhere(' c.fromWorkingDateMonday >= ?2 and ?2 <= c.toWorkingDateMonday');
                            break;
                        }
                        case "2":{
                            $qb->andWhere(' c.fromWorkingDateTuesday >= ?2 and ?2 <= c.toWorkingDateTuesday');
                            break;
                        }
                        case "3":{
                            $qb->andWhere(' c.fromWorkingDateWednesday >= ?2 and ?2 <= c.toWorkingDateWednesday');
                            break;
                        }
                        case "4":{
                            $qb->andWhere(' c.fromWorkingDateThursday >= ?2 and ?2 <= c.toWorkingDateThursday');
                            break;
                        }
                        case "5":{
                            $qb->andWhere('  c.fromWorkingDateFriday >= ?2 and ?2 <= c.toWorkingDateFriday');
                            break;
                        }
                        case "6":{
                            $qb->andWhere(' c.fromWorkingDateSaturday >= ?2 and ?2 <= c.toWorkingDateSaturday');
                            break;
                        }
                    };
                    // the function returns a QueryBuilder object
                    $qb->setParameter('1', $restaurant->getAddress())
                        ->setParameter('2', $date->format('H:i:s'))
                        ->orderBy('c.id', 'ASC');
                    return $qb;
                }))
            ->add('waitingTime')
            ->add('status', ChoiceType::class, [
                'multiple' => false,
                'choices' => [
                    'ordered'=> OrderStatus::$ORDERED,
                    'accepted'=> OrderStatus::$ACCEPTED,
                    'in_progress'=> OrderStatus::$IN_PROGRESS,
                    'done'=> OrderStatus::$DONE
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Suborder::class,
        ]);
    }
}
