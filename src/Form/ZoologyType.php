<?php
// src/Form/ZoologyType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Entity\Zoology;
use App\Entity\Lkppristupnost;
use App\Entity\Lkpzoochar;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ZoologyType  extends AbstractType
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }  

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $request = $this->requestStack->getCurrentRequest();
        $aPom = $request->getLocale();  
        if ($aPom == 'sk')
        {
        $builder
            ->add('zoology_locality',TextType::class,[ 'label' => 'nazov.lokality'])
	    ->add('zoology_date',DateType::class,[ 
		    'label' => 'zoology.date',
		    'widget' => 'single_text',
                    'html5' => false,
		    'attr' => [
			    'data-provide' => 'datepicker', 
			    'data-date-format' => 'dd.mm.yyyy',
			    'data-date-start-date' => '0',
			    'data-date-calendar-weeks'  => 1,
                            'data-date-default-view-date'  => 'today',
                            'data-date-end-date' => '0d',
			    'data-date-today-highlight' => true,
			    'data-date-language' => 'sk',
		    ],
 	    ])
            ->add('zoology_longitud',NumberType::class, ['label' => 'zoology.longitud'])
            ->add('zoology_latitud',NumberType::class, ['label' => 'zoology.latitud'])
            ->add('zoology_description',TextType::class, ['label' => 'zoology.description'])
	    ->add('zoology_accessibility', EntityType::class, [
	      'class' => Lkppristupnost::class,
	      'choice_label' => function ($lkppristupnost){
		       return $lkppristupnost->getLkppristupnostPopissk(); 
               },
              'label' => 'zoology.acces'
	      ])
            ->add('lkpzoospecies_id')
            ->add('count', IntegerType::class, ['label' => 'pocet'])
	    ->add('lkpzoochar_id', EntityType::class, [
	      'class' => Lkpzoochar::class,
	      'choice_label' =>  function($lkpzoochar){ 
		 return $lkpzoochar->getLkpzoocharIdCh().' - '.$lkpzoochar->getLkpzoocharPopularmeaning();
	      },
              'label' => 'charakteristika'
	      ]
             )
            ->add('description',TextType::class, ['label' => 'zoospecies.description'])
            ->add('save', SubmitType::class, ['label' => 'save'])
            ->add('reset', ResetType::class, ['label' => 'reset'])
        ;
	    }
            else
           {
	   $builder
            ->add('zoology_locality',TextType::class,[ 'label' => 'nazov.lokality'])
	    ->add('zoology_date',DateType::class,[ 
		    'label' => 'zoology.date',
		    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
			    'data-provide' => 'datepicker', 
			    'data-date-start-date' => '0',
			    'data-date-calendar-weeks'  => 1,
                            'data-date-default-view-date'  => 'today',
                            'data-date-end-date' => '0d',
			    'data-date-today-highlight' => true,
			    'data-date-language' => 'en',
		    ],
 	    ])
            ->add('zoology_longitud',NumberType::class, ['label' => 'zoology.longitud'])
            ->add('zoology_latitud',NumberType::class, ['label' => 'zoology.latitud'])
            ->add('zoology_description',TextType::class, ['label' => 'zoology.description'])
	    ->add('zoology_accessibility', EntityType::class, [
	      'class' => Lkppristupnost::class,
	      'choice_label' => function ($lkppristupnost){
		       return $lkppristupnost->getLkppristupnostPopisen(); 
               },
              'label' => 'zoology.acces'
	      ])
            ->add('lkpzoospecies_id')
            ->add('count', IntegerType::class, ['label' => 'pocet'])
            ->add('lkpzoochar_id', EntityType::class, [
	      'class' => Lkpzoochar::class,
	      'choice_label' =>  function($lkpzoochar){ 
		 return $lkpzoochar->getLkpzoocharIdCh().' - '.$lkpzoochar->getLkpzoocharPopularmeaningen();
	      },
              'label' => 'charakteristika'
	      ]
             )
            ->add('description',TextType::class, ['label' => 'zoospecies.description'])
            ->add('save', SubmitType::class, ['label' => 'save'])
            ->add('reset', ResetType::class, ['label' => 'reset'])
        ;	   
      }
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Zoology::class,
        ]);
    }
}

?>
