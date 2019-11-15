<?php

namespace App\Form;

use App\Entity\Impsubory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use App\Form\HiddenDateTimeType;


class ImpsuboryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('created', DateTimeType::class, [
            'data'     => new \DateTime(), 
            'label'    => 'created',
            'disabled' => true,
            'html5'    => false,
  		      'widget'   => 'single_text',
            'format'   => 'd.M.yyyy HH:mm'

          ])
            ->add('impsubor',null,[ 
              'data' => 'Načítajte si Váš excel súbor, jeho názov bude automaticky vyplnený po úspešnom uložení',
              'label' => 'Názov po importe'
              ])
            ->add('subor', FileType::class, [
                  'label'    => 'Import (načítajte Excel súbor)',
                  'mapped'   => false,
                  'required' => false,
                  'constraints' => [
                    new File([
                        'maxSize' => '3M',
                        'mimeTypes' => [
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Excel document',
                    ])
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Impsubory::class,
        ]);
    }
}
