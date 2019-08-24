<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This is a lookup table, list of birds in Slovakia
 *
 *
 *
 * @ORM\Entity(repositoryClass="App\Repository\LkpzoospeciesAvesRepository")
 *
 */
class LkpzoospeciesAves
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Contains genus and species name of bird
     * 
     * @example Accipiter gentilis
     *
     * @param string $lkpzoospecies_genus_species Rod druh 
     *
     * @ORM\Column(type="string", length=255, options={"comment":"Contains genus and species of bird"})
     *
     */
    private $lkpzoospecies_genus_species;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @example Parus major Linnaeus 1758
     */
    private $lkpzoospecies_lat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lkpzoospecies_sk;

    /**
     * @ORM\Column(type="integer")
     */
    private $lkpzoospecies_dynamic_id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $lkpzoospecies_subspecorder;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $lkpzoospecies_najc;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLkpzoospeciesGenusSpecies(): ?string
    {
        return $this->lkpzoospecies_genus_species;
    }

    public function setLkpzoospeciesGenusSpecies(string $lkpzoospecies_genus_species): self
    {
        $this->lkpzoospecies_genus_species = $lkpzoospecies_genus_species;

        return $this;
    }

    public function getLkpzoospeciesLat(): ?string
    {
        return $this->lkpzoospecies_lat;
    }

    public function setLkpzoospeciesLat(string $lkpzoospecies_lat): self
    {
        $this->lkpzoospecies_lat = $lkpzoospecies_lat;

        return $this;
    }

    public function getLkpzoospeciesSk(): ?string
    {
        return $this->lkpzoospecies_sk;
    }

    public function setLkpzoospeciesSk(string $lkpzoospecies_sk): self
    {
        $this->lkpzoospecies_sk = $lkpzoospecies_sk;

        return $this;
    }

    public function getLkpzoospeciesDynamicId(): ?int
    {
        return $this->lkpzoospecies_dynamic_id;
    }

    public function setLkpzoospeciesDynamicId(int $lkpzoospecies_dynamic_id): self
    {
        $this->lkpzoospecies_dynamic_id = $lkpzoospecies_dynamic_id;

        return $this;
    }

    public function getLkpzoospeciesSubspecorder(): ?int
    {
        return $this->lkpzoospecies_subspecorder;
    }

    public function setLkpzoospeciesSubspecorder(?int $lkpzoospecies_subspecorder): self
    {
        $this->lkpzoospecies_subspecorder = $lkpzoospecies_subspecorder;

        return $this;
    }

    public function getLkpzoospeciesNajc(): ?int
    {
        return $this->lkpzoospecies_najc;
    }

    public function setLkpzoospeciesNajc(?int $lkpzoospecies_najc): self
    {
        $this->lkpzoospecies_najc = $lkpzoospecies_najc;

        return $this;
    }
}
