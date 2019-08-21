<?php
// src/Form/SpeciesSelectTextType.php
namespace App\Form;


use App\Form\DataTransformer\TaxnameToSpeciesTransformer;
use App\Repository\LkpzoospeciesAvesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SpeciesSelectTextType  extends AbstractType
{
    private $lkpzoospeciesAvesRepository;
    public function __construct(LkpzoospeciesAvesRepository $lkpzoospeciesAvesRepository)
    {
        $this->lkpzoospeciesAvesRepository = $lkpzoospeciesAvesRepository;
    }
  public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new TaxnameToSpeciesTransformer($this->lkpzoospeciesAvesRepository));
    }
  public function getParent()
  {
      return TextType::class;
  }
}
