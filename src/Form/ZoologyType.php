<?php
// src/Form/ZoologyType.php
namespace App\Form;

use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\AbstractType;
use App\Form\HiddenDateTimeType;
use App\Entity\Zoology;
use App\Entity\LkpzoospeciesAves;
use App\Entity\Lkppristupnost;
use App\Entity\Lkpzoochar;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
          ->add('zoology_longitud', 
                $options['disable_field'] == true ? HiddenType::class : null, 
                $options['disable_field'] == true ? [] : [
                    'label' => 'zoology.longitud', 
                    'scale' => 5,
                    'disabled' => $options['disable_field']
                  ]
                )
                ->add('zoology_latitud',
                $options['disable_field'] == true ? HiddenType::class : null, 
                $options['disable_field'] == true ? [] : [
                    'label' => 'zoology.latitud', 
                    'scale' => 5,
                    'disabled' => $options['disable_field']
                  ]
                )
                ->add('zoology_locality',
                 $options['disable_field'] == true ? HiddenType::class : null,                  
                 $options['disable_field'] == true ? [] : [ 
              'label' => 'nazov.lokality',
              'disabled' => $options['disable_field']
            ])
            ->add('zoology_date',
                 $options['disable_field'] == true ? HiddenDateTimeType::class : DateType::class,
                 $options['disable_field'] == true ? ['required' => true] :    [ 
		    'label' => 'zoology.date',
                    'disabled' => $options['disable_field'],
                    'data' => (isset($options['data']) && $options['data']->getZoologyDate() !== null) ? $options['data']->getZoologyDate() :new \DateTime(),   
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
                            'disableTouchKeyboard' => true,
                            'Readonly' => true,
                            'keepOpen' => false,
                            'autoclose' => true,
		    ],
                  ])
              ->add('zoology_description',
                $options['disable_field'] == true ? HiddenType::class : null, 
                $options['disable_field'] == true ? [] : [
              'label' => 'zoology.description',
             'disabled' => $options['disable_field']
            ])
            ->add('nieHnedDoAvesu',
              CheckboxType::class, [
              'label'    => 'nieje.pripraveny.hned.do.Avesu',
              'required' => false,
              'mapped'   => false
              ])
	    ->add('zoology_accessibility', EntityType::class, [
	      'class' => Lkppristupnost::class,
	      'choice_label' => function ($lkppristupnost){
		       return $lkppristupnost->getLkppristupnostPopissk(); 
               },
              'label' => 'zoology.acces'
	      ])
              ->add('lkpzoospecies_id', EntityType::class, [
	      'class' => LkpzoospeciesAves::class,
              'placeholder' => 'druh.vyzva.polozka',
	      'choice_label' => function ($lkpzoospeciesAves){
		       return $lkpzoospeciesAves->getLkpzoospeciesSk().' ('.$lkpzoospeciesAves->getLkpzoospeciesLat().')'; 
              },
	      'query_builder' => function (EntityRepository $er) {
		      return $er->createQueryBuilder('d')
                              ->where('d.lkpzoospecies_najc <= 260')
			      ->orderBy('d.lkpzoospecies_subspecorder', 'ASC');
	      },
              ])
	    ->add('count', null, [
		    'label' => 'pocet',
                    'data' => (isset($options['data']) && $options['data']->getCount() !== null) ? $options['data']->getCount() : 1   
	    ])
	    ->add('lkpzoochar_id', EntityType::class, [
	      'class' => Lkpzoochar::class,
	      'choice_label' =>  function($lkpzoochar){ 
		 return $lkpzoochar->getLkpzoocharIdCh().' - '.$lkpzoochar->getLkpzoocharPopularmeaning();
	      },
	      'query_builder' => function (EntityRepository $er) {
		      return $er->createQueryBuilder('u')
			      ->orderBy('u.lkpzoochar_comborder', 'ASC');
	      },
              'label' => 'charakteristika',
/*              'preferred_choices' => function (EntityRepository $er){
        		      $res = $er->createQueryBuilder('u')
			      ->where('u.id =', 36 );             
                              var_dump($res);
              },
 */
              /* nedarí sa
              'data' => (isset($options['data']) && $options['data']->getLkpzoocharId() !== null) ? $options['data']->getLkpzoocharId() : function (EntityRepository $em) {
                var_dump($em->getReference(Lkpzoochar::class, 36 ));
              }
        */
            //  'placeholder' => 'charakteristika.vyzva.polozka',
     // nedarí sa        'data' => 'M_MV' // $em->getReference(Lkpzoochar, 36)
	      ]
             )
            ->add('description',null, ['label' => 'zoospecies.description'])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn-success btn'], 'label' => 'save'])
            ->add('saveAndAdd', SubmitType::class, ['attr' => ['class' => 'btn-success btn'], 'label' => 'Save.And.Add.Species'])
            ->add('reset', ResetType::class, ['label' => 'reset'])
        ;
	    }
            else
           {
	   $builder
             ->add('zoology_longitud',
                    $options['disable_field'] == true ? HiddenType::class : null, 
                    $options['disable_field'] == true ? [] : [
              'label' => 'zoology.longitud',
              'scale' => 5,
              'disabled' => $options['disable_field']
            ])
            ->add('zoology_latitud',
                     $options['disable_field'] == true ? HiddenType::class : null,
                     $options['disable_field'] == true ? [] : [
              'label' => 'zoology.latitud',
              'scale' => 5,
              'disabled' => $options['disable_field']
            ])
            ->add('zoology_locality',
                 $options['disable_field'] == true ? HiddenType::class : null,                  
                 $options['disable_field'] == true ? [] :[ 
              'label' => 'nazov.lokality',
              'disabled' => $options['disable_field']
            ])
	    ->add('zoology_date',DateType::class,[ 
		    'label' => 'zoology.date',
                    'disabled' => $options['disable_field'],
                    'data' => (isset($options['data']) && $options['data']->getZoologyDate() !== null) ? $options['data']->getZoologyDate() :new \DateTime(),   
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
			    'data-date-language' => 'en',
			    'data-date-today-btn' => 'linked',
                            'disableTouchKeyboard' => true,
                            'Readonly' => true,
                            'keepOpen' => false,
                            'autoclose' => true
		    ],
 	    ])
            ->add('zoology_description',
                $options['disable_field'] == true ? HiddenType::class : null, 
                $options['disable_field'] == true ? [] : [
              'label' => 'zoology.description',
              'disabled' => $options['disable_field']
            ])
         ->add('nieHnedDoAvesu',
              CheckboxType::class, [
              'label' => 'nieje.pripraveny.hned.do.Avesu',
              'mapped'   => false
              ])

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
                              ->where('d.lkpzoospecies_najc <= 260')
			      ->orderBy('d.lkpzoospecies_subspecorder', 'ASC');
	      },
              ])
              ->add('count', null, [
                'label' => 'pocet',
                'data' => (isset($options['data']) && $options['data']->getCount() !== null) ? $options['data']->getCount() : 1   
              ])
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
        // netreba, aby bola prvá položka tá doporučovaná      'placeholder' => 'charakteristika.vyzva.polozka',
	      ]
             )
            ->add('description',null, ['label' => 'zoospecies.description'])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn-danger btn'], 'label' => 'save'])
            ->add('saveAndAdd', SubmitType::class, ['attr' => ['class' => 'btn-success btn'], 'label' => 'Save.And.Add.Species'])
            ->add('reset', ResetType::class, ['label' => 'reset'])
        ;	   
      }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Zoology::class,
            'disable_field' => false
        ]);
    }
}

?>
