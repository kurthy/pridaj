<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The lookup table for charakteristic of observation. This charakteristic help to categorize e. g. it is breeding, 
 * wintering or migratin bird, level of evidence of breeding. The ID is stored to the observation, other fields are 
 * describing the value. The most advanced user known the value of field lkpzoocharIdCh. In combination with
 * value of field lkpzoocharMeaning is enough to understand by user at choice. The field lkpzoocharComborder help the
 * sorting by logical sequence no alphabet. Other fields describe more detail the meaning and in more languages.In Slovak
 * číselník charakteristík pozorovania, užívateľ si volí podľa polí IdCh a Meaning, do databázy ukladať hodnotu ID.
 *
 *
 *
 * @ORM\Entity(repositoryClass="App\Repository\LkpzoocharRepository")
 */
class Lkpzoochar
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $lkpzoochar_id_ch;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $lkpzoochar_meaning;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $lkpzoochar_popularmeaning;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lkpzoochar_description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lkpzoochar_expertise;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $lkpzoochar_comborder;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $lkpzoochar_popularmeaningde;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $lkpzoochar_popularmeaningen;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $lkpzoochar_popularmeaninghu;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLkpzoocharIdCh(): ?string
    {
        return $this->lkpzoochar_id_ch;
    }

    public function setLkpzoocharIdCh(string $lkpzoochar_id_ch): self
    {
        $this->lkpzoochar_id_ch = $lkpzoochar_id_ch;

        return $this;
    }

    public function getLkpzoocharMeaning(): ?string
    {
        return $this->lkpzoochar_meaning;
    }

    public function setLkpzoocharMeaning(?string $lkpzoochar_meaning): self
    {
        $this->lkpzoochar_meaning = $lkpzoochar_meaning;

        return $this;
    }

    public function getLkpzoocharPopularmeaning(): ?string
    {
        return $this->lkpzoochar_popularmeaning;
    }

    public function setLkpzoocharPopularmeaning(?string $lkpzoochar_popularmeaning): self
    {
        $this->lkpzoochar_popularmeaning = $lkpzoochar_popularmeaning;

        return $this;
    }

    public function getLkpzoocharDescription(): ?string
    {
        return $this->lkpzoochar_description;
    }

    public function setLkpzoocharDescription(?string $lkpzoochar_description): self
    {
        $this->lkpzoochar_description = $lkpzoochar_description;

        return $this;
    }

    public function getLkpzoocharExpertise(): ?string
    {
        return $this->lkpzoochar_expertise;
    }

    public function setLkpzoocharExpertise(string $lkpzoochar_expertise): self
    {
        $this->lkpzoochar_expertise = $lkpzoochar_expertise;

        return $this;
    }

    public function getLkpzoocharComborder(): ?int
    {
        return $this->lkpzoochar_comborder;
    }

    public function setLkpzoocharComborder(?int $lkpzoochar_comborder): self
    {
        $this->lkpzoochar_comborder = $lkpzoochar_comborder;

        return $this;
    }

    public function getLkpzoocharPopularmeaningde(): ?string
    {
        return $this->lkpzoochar_popularmeaningde;
    }

    public function setLkpzoocharPopularmeaningde(?string $lkpzoochar_popularmeaningde): self
    {
        $this->lkpzoochar_popularmeaningde = $lkpzoochar_popularmeaningde;

        return $this;
    }

    public function getLkpzoocharPopularmeaningen(): ?string
    {
        return $this->lkpzoochar_popularmeaningen;
    }

    public function setLkpzoocharPopularmeaningen(?string $lkpzoochar_popularmeaningen): self
    {
        $this->lkpzoochar_popularmeaningen = $lkpzoochar_popularmeaningen;

        return $this;
    }

    public function getLkpzoocharPopularmeaninghu(): ?string
    {
        return $this->lkpzoochar_popularmeaninghu;
    }

    public function setLkpzoocharPopularmeaninghu(?string $lkpzoochar_popularmeaninghu): self
    {
        $this->lkpzoochar_popularmeaninghu = $lkpzoochar_popularmeaninghu;

        return $this;
    }

}
