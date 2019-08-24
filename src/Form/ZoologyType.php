<?php
// src/Form/ZoologyType.php
namespace App\Form;

use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\AbstractType;
use App\Entity\Zoology;
use App\Entity\LkpzoospeciesAves;
use App\Entity\Lkppristupnost;
use App\Entity\Lkpzoochar;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityRepository;

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
            ->add('zoology_locality',null,[ 'label' => 'nazov.lokality', 'data' => 'Testovacia lokalita'])
	    ->add('zoology_date',DateType::class,[ 
		    'label' => 'zoology.date',
		    'data' => new \DateTime(), 
		    'widget' => 'single_text',
                    'html5' => false,
		    'attr' => [
			    'data-provide' => 'datepicker', 
			    'data-date-format' => 'yyyy-mm-dd',
			    'data-date-start-date' => '0',
			    'data-date-calendar-weeks'  => 1,
                            'data-date-default-view-date'  => 'today',
                            'data-date-end-date' => '0d',
			    'data-date-today-highlight' => true,
			    'data-date-language' => 'sk',
			    'data-date-today-btn' => 'linked',
		    ],
 	    ])
            ->add('zoology_longitud',null, ['label' => 'zoology.longitud', 'data' => '48.12345'])
            ->add('zoology_latitud',null, ['label' => 'zoology.latitud', 'data' => '17.12345'])
            ->add('zoology_description',null, ['label' => 'zoology.description', 'data' => 'Popis lokality ...'])
	    ->add('zoology_accessibility', EntityType::class, [
	      'class' => Lkppristupnost::class,
	      'choice_label' => function ($lkppristupnost){
		       return $lkppristupnost->getLkppristupnostPopissk(); 
               },
              'label' => 'zoology.acces'
	      ])
              ->add('lkpzoospecies_id',null, [
              'placeholder' => 'druh.vyzva.polozka',
	      'choice_label' => function ($lkpzoospeciesAves){
		       return $lkpzoospeciesAves->getLkpzoospeciesSk().' ('.$lkpzoospeciesAves->getLkpzoospeciesLat().')'; 
              },
	      'query_builder' => function (EntityRepository $er) {
		      return $er->createQueryBuilder('d')
                              ->where('d.lkpzoospecies_najc <= 340')
			      ->orderBy('d.lkpzoospecies_subspecorder', 'ASC');
	      },
              ]) //, SpeciesSelectTextType::class)
	    ->add('count', null, [
		    'label' => 'pocet',
		    'data' => 1,
	    ])
	   // ->add('lkpzoochar_id')
	    ->add('lkpzoochar_id', EntityType::class, [
	      'class' => Lkpzoochar::class,
	      'choice_label' =>  function($lkpzoochar){ 
		 return $lkpzoochar->getLkpzoocharIdCh().' - '.$lkpzoochar->getLkpzoocharPopularmeaning();
	      },
	      'query_builder' => function (EntityRepository $er) {
		      return $er->createQueryBuilder('u')
			      ->orderBy('u.lkpzoochar_comborder', 'ASC');
	      },
              'label' => 'charakteristika'
	      ]
             )
            ->add('description',null, ['label' => 'zoospecies.description', 'data' => 'Tu bude poznámka k druhu..'])
            ->add('save', SubmitType::class, ['label' => 'save'])
            ->add('reset', ResetType::class, ['label' => 'reset'])
        ;
	    }
            else
           {
	   $builder
            ->add('zoology_locality',null,[ 'label' => 'nazov.lokality', 'data' => 'Test locality'])
	    ->add('zoology_date',DateType::class,[ 
		    'label' => 'zoology.date',
		    'widget' => 'single_text',
		    'data' => new \DateTime(), 
                    'html5' => false,
                    'attr' => [
			    'data-provide' => 'datepicker', 
			    'data-date-format' => 'yyyy-mm-dd',
			    'data-date-start-date' => '0',
			    'data-date-calendar-weeks'  => 1,
                            'data-date-default-view-date'  => 'today',
                            'data-date-end-date' => '0d',
			    'data-date-today-highlight' => true,
			    'data-date-language' => 'en',
			    'data-date-today-btn' => 'linked',
		    ],
 	    ])
            ->add('zoology_longitud',null, ['label' => 'zoology.longitud', 'data' => '48.12345'])
            ->add('zoology_latitud',null, ['label' => 'zoology.latitud', 'data' => '17.12345'])
            ->add('zoology_description',null, ['label' => 'zoology.description', 'data' => 'Notices about lokality ...'])
	    ->add('zoology_accessibility', EntityType::class, [
	      'class' => Lkppristupnost::class,
	      'choice_label' => function ($lkppristupnost){
		       return $lkppristupnost->getLkppristupnostPopisen(); 
               },
              'label' => 'zoology.acces'
	      ])
              ->add('lkpzoospecies_id', null, [
                            'placeholder' => 'druh.vyzva.polozka',
	      'choice_label' => function ($lkpzoospeciesAves){
		       return $lkpzoospeciesAves->getLkpzoospeciesLat(); 
              },
	      'query_builder' => function (EntityRepository $er) {
		      return $er->createQueryBuilder('d')
                              ->where('d.lkpzoospecies_najc <= 340')
			      ->orderBy('d.lkpzoospecies_subspecorder', 'ASC');
	      },
              ])
            ->add('count', null, ['label' => 'pocet', 'data' => 1,])
            ->add('lkpzoochar_id', EntityType::class, [
	      'class' => Lkpzoochar::class,
	      'choice_label' =>  function($lkpzoochar){ 
		 return $lkpzoochar->getLkpzoocharIdCh().' - '.$lkpzoochar->getLkpzoocharPopularmeaningen();
	      },
              'label' => 'charakteristika',
	      'query_builder' => function (EntityRepository $er) {
		      return $er->createQueryBuilder('u')
			      ->orderBy('u.lkpzoochar_comborder', 'ASC');
	      },
	      ]
             )
            ->add('description',null, ['label' => 'zoospecies.description', 'data' => 'Notice about observed species..'])
            ->add('save', SubmitType::class, ['label' => 'save'])
            ->add('reset', ResetType::class, ['label' => 'reset'])
        ;	   
      }

        $builder->get('lkpzoochar_id')
          ->addModelTransformer(new CallbackTransformer(
            function ($lkpzoocharIdod) {
            return $lkpzoocharIdod; 
            }, 
            function ($lkpzoocharIddo) {
              //dd($lkpzoocharIddo);
              return $lkpzoocharIddo->getId();
            }
          ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Zoology::class,
        ]);
    }
}

?>