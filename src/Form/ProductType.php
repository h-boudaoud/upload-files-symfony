<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class ,[
                    'attr' =>['class' => "form-control"]
                ]
            )
            ->add('description', TextareaType::class, [
                'attr' =>['class' => "form-control"]
                    ]
            )
            ->add('price', NumberType::class, [
                'attr' =>
                    ['class' => "form-control", 'grouping' => true, 'type' => 'number',
                        'min' => "0",'max' => 1000, 'step' => .01
                    ],
                'html5' => true,
            ])
            ->add('storedValue', NumberType::class, [
                'attr' =>
                    ['class' => "form-control", 'grouping' => true, 'type' => 'number',
                        'min' => "0",'max' => 1000, 'step' => 1
                    ],
                'html5' => true,
            ])
            ->add('storedAlarm', NumberType::class, [
                'attr' =>
                    ['class' => "form-control", 'grouping' => true, 'type' => 'number',
                        'min' => "0",'max' => 1000, 'step' => 1
                    ],
                'html5' => true,
            ])
            ->add('published', ChoiceType::class, [
                    'attr' =>['class' => "form-control"],
                    'choices' => [
                        'Published' => true,
                        'Not published' => false,
                    ],
                ]
            )
            ->add('images', ImageType::class,[
                'label' => 'Add image',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,


                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                //'constraints' => [ new Image() ],
            ])
//            ->add('category', ChoiceType::class, [
//                    'choices' => 'categories',
//                    'choice_value' => 'name',
//                    'choice_label' => function (?Category $category) {
//                        return $category ? strtoupper($category->getName()) : '';
//                    },
//                ]
//            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'categories' => Category::class
        ]);
    }
}
