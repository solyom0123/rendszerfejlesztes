<?php

namespace App\Form;

use App\Entity\Food;
use App\Entity\FoodImages;
use App\Repository\FoodRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FoodImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file',FileType::class,array(
                'mapped' => false,
                'multiple'=>false,
                'required'=>true,
                'constraints' => [
                    new File([
                        'maxSize' => '20000k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image document',
                    ])
                ],
            ))
            ->add('food', EntityType::class, array(
                'class' => Food::class,
                'placeholder'=> 'Kérem válasszon!',
                'required'=>true,
                'query_builder' => function (FoodRepository $repository) use($options){
                    $qb = $repository->createQueryBuilder('c');
                    // the function returns a QueryBuilder object
                    return $qb
                        // find all users where 'deleted' is NOT '1'
                        ->where($qb->expr()->eq('c.restaurant', '?1'))
                        ->setParameter('1', $options["company"])
                        ->orderBy('c.id', 'ASC');
                }))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FoodImages::class,
            'company' => Food::class,
        ]);
    }

}
