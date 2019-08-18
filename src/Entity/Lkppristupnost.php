<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lookup table, contains 5 types of accessibility. Accessibility is a status given by author (observator) of species
 * or by list of threatened species if zoological characteristic speaks about breeding. Field lkppristupnost_pristupnost
 * contains an abbreviation of this accessibility, e.g. "V" - public. The field lkppristupnost_popissk contains a 
 * description in slovak langauge, the field lkppristupnost_popisen in english langauge. Into observation is saved a 
 * value of field lkppristupnost_pristupnost (not the value of ID!). In Slovak: číselník pre prístupnosť, ktorú si 
 * volí užívateľ pre svoje pozorovanie, do databázy ukladáme hodnotu položky lkppristupnost_pristupnost, nie id.
 * 
 * @ORM\Entity(repositoryClass="App\Repository\LkppristupnostRepository")
 */
class Lkppristupnost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $lkppristupnost_pristupnost;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $lkppristupnost_popissk;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $lkppristupnost_popisen;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLkppristupnostPristupnost(): ?string
    {
        return $this->lkppristupnost_pristupnost;
    }

    public function setLkppristupnostPristupnost(string $lkppristupnost_pristupnost): self
    {
        $this->lkppristupnost_pristupnost = $lkppristupnost_pristupnost;

        return $this;
    }

    public function getLkppristupnostPopissk(): ?string
    {
        return $this->lkppristupnost_popissk;
    }

    public function setLkppristupnostPopissk(string $lkppristupnost_popissk): self
    {
        $this->lkppristupnost_popissk = $lkppristupnost_popissk;

        return $this;
    }

    public function getLkppristupnostPopisen(): ?string
    {
        return $this->lkppristupnost_popisen;
    }

    public function setLkppristupnostPopisen(string $lkppristupnost_popisen): self
    {
        $this->lkppristupnost_popisen = $lkppristupnost_popisen;

        return $this;
    }

    public function __toString()
    {
      return $this->getLkppristupnostPristupnost();
    }
}
