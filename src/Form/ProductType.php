<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Service\PrincipalCategories;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                    'attr' => ['class' => "form-control"]
                ]
            )
            ->add('description', TextareaType::class, [
                    'attr' => ['class' => "form-control"]
                ]
            )
            ->add('price', NumberType::class, [
                'attr' =>
                    ['class' => "form-control", 'grouping' => true, 'type' => 'number',
                        'min' => "0", 'max' => 1000, 'step' => .01
                    ],
                'html5' => true,
            ])
            ->add('storedValue', NumberType::class, [
                'attr' =>
                    ['class' => "form-control", 'grouping' => true, 'type' => 'number',
                        'min' => "0", 'max' => 1000, 'step' => 1
                    ],
                'html5' => true,
            ])
            ->add('storedAlarm', NumberType::class, [
                'attr' =>
                    ['class' => "form-control", 'grouping' => true, 'type' => 'number',
                        'min' => "0", 'max' => 1000, 'step' => 1
                    ],
                'required' => false,
                'html5' => true,
            ])
            ->add('published', ChoiceType::class, [
                    'attr' => ['class' => "form-control"],
                    'choices' => [
                        'Published' => true,
                        'Not published' => false,
                    ],
                ]
            )
            ->add('category', EntityType::class, [
                    'attr' => ['class' => "form-control"],
                    'class' => Category::class,

                    // uses the User.username property as the visible option string
                    'choice_label' => 'labelForm',

                    // Create a custom query to use when fetching the entities
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->where('c.subCategory > 1')
                            // ->groupBy('c.subCategory')
                            ->orderBy('c.subCategory , c.name', 'ASC');
                    },
                    //used to render a select box, check b oxes or radios
                    'multiple' => false,
                    'expanded' => false,
                ]
            )
            ->add('images', ImageType::class, [
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
        ;


//            ->add('saveTo', ChoiceType::class, [
//                'attr' => ['class' => 'form-control js_image_saveTo'],
//                'choices' => [
//                    'Folder (file)' => 'folder',
//                    'Database (Binary)' => 'database',
//                ],
//                'placeholder' => false,
////                'placeholder' => 'Choose an option',
//                'label' => 'Save to ',
//                'required' => false,
//
//            ])
//            ->add('files', FileType::class, [
//                'attr' => ['class' => 'js-upload'],
//                'label' => 'Images',
//                'data_class' => null,
//                'multiple' => true,
//
//                // unmapped means that this field is not associated to any entity property
//                'mapped' => true,
//                // make it optional so you don't have to re-upload the PDF file
//                // every time you edit the Product details
//                'required' => false,
//
//                // unmapped fields can't define their validation using annotations
//                // in the associated entity, so you can use the PHP constraint classes
//
//                'constraints' => new Image(),
////                'constraints' => [
////                    new File(
////                        [
////                            'maxSize' => '2M',
////                            'maxSizeMessage' => 'File saving failed. The location chosen for recording is not authorized',
////                            'mimeTypes' => Image::MIME_TYPES,
////                            'mimeTypesMessage' => 'File saving failed. Allowed file extensions are :.jpg, .gif, .ico, .png, .svg, .pdf',
////                        ]
////                    )
////                ],
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'categories' => Category::class
        ]);

//        $resolver->setDefaults([
//            'data_class' => Product::class,
//            'validation_groups' => [
//                Product::class,
//                Image::class,
//                'determineValidationGroups',
//            ],
//        ]);
    }
}
