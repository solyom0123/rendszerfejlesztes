<?php

namespace App\Form;

use App\Entity\CourierData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourierDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location')
            ->add('mobileNumber')
            ->add('name')
            ->add('vehicleType')
            ->add('fromWorkingDateMonday')
            ->add('toWorkingDateMonday')
            ->add('fromWorkingDateTuesday')
            ->add('toWorkingDateTuesday')
            ->add('fromWorkingDateWednesday')
            ->add('toWorkingDateWednesday')
            ->add('fromWorkingDateThursday')
            ->add('toWorkingDateThursday')
            ->add('fromWorkingDateFriday')
            ->add('toWorkingDateFriday')
            ->add('fromWorkingDateSaturday')
            ->add('toWorkingDateSaturday')
            ->add('fromWorkingDateSunday')
            ->add('toWorkingDateSunday')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CourierData::class,
        ]);
    }
}
