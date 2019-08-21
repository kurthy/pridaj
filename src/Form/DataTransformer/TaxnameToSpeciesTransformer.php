<?php

namespace App\Form\DataTransformer;

use App\Entity\LkpzoospeciesAves;
use App\Repository\LkpzoospeciesAvesRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TaxnameToSpeciesTransformer implements DataTransformerInterface
{
    private $lkpzoospeciesAvesRepository;

    public function __construct(LkpzoospeciesAvesRepository $lkpzoospeciesAvesRepository)
    {
        $this->lkpzoospeciesAvesRepository = $lkpzoospeciesAvesRepository;
    }
    public function transform($value)
    {
       if (null === $value) {
            return '';
        }
        if (!$value instanceof LkpzoospeciesAves) {
            throw new \LogicException('The SpeciesSelectTextType can only be used with species objects');
        }
        return $value->getLkpzoospeciesGenusSpecies();
      }
    public function reverseTransform($value)
    {
        $species = $this->lkpzoospeciesAvesRepository->findOneBy(['id' => $value]);
        if (!$species) {
            throw new TransformationFailedException(sprintf('No species found with taxname "%s"', $value));
        }
        return $species;
    }
}

?>
