<?php

namespace App\Form;

use App\Entity\Suborder;
use App\Enums\OrderStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SuborderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
