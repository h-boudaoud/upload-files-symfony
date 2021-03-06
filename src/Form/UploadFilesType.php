<?php

namespace App\Form;


use App\Service\UploadArrayFiles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UploadFilesType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('saveTo',ChoiceType::class, [
                'attr'=> ['class'=>'form-control js_image_saveTo'],
                'choices'  => [
                    'Folder (file)' => 'folder',
                    'Database (Binary)' => 'database',
                ],
                'placeholder' => false,
//                'placeholder' => 'Choose an option',
                'label' => 'Save to ',
                'required' => false,

            ])
            ->add('files', FileType::class,[
                'attr'=> [
                    'class'=>'',
                    'data-show-upload'=>'true',
                    'data-show-caption'=>'true'
                ],
                'required' => false,
                'label' => 'Upload new file',
                'multiple' =>  'multiple'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UploadArrayFiles::class,
        ]);
    }
}
