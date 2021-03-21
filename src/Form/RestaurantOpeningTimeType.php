<?php

namespace App\Form;

use App\Entity\RestaurantOpeningTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestaurantOpeningTimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('day')
            ->add('openTime')
            ->add('closeTime')
            ->add('restaurant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RestaurantOpeningTime::class,
        ]);
    }
}
