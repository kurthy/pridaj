<?php

namespace App\Form;

use App\Entity\LkpzoospeciesAves;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LkpzoospeciesAvesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lkpzoospecies_genus_species')
            ->add('lkpzoospecies_lat')
            ->add('lkpzoospecies_sk')
            ->add('lkpzoospecies_dynamic_id')
            ->add('lkpzoospecies_subspecorder')
            ->add('lkpzoospecies_najc')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LkpzoospeciesAves::class,
        ]);
    }
}
