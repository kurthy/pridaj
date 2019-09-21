<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Zoology - database of observations
 *
 * @ORM\Entity(repositoryClass="App\Repository\ZoologyRepository")
 */
class Zoology
{
    /**
     * @var int The id of this zoology observation, generated value.
     * 
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int The id of registered user of AVES Symfonia database.
     * 
     * @ORM\Column(type="integer", nullable=true, options={"comment":"Id of registered user of AVES Symfony database"})
     * @Assert\GreaterThan(0)
     */
    private $sf_guard_user_id;

    /**
     * @var date The date of observation.
     *
     * @ORM\Column(type="date", options={"comment":"The data of observation."})
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    private $zoology_date;

    /**
     * @var float The geographic x coordinate, WGS84, eg. 17.13245
     *
     * @ORM\Column(type="float", options={"comment":"The geographic x coordinate, WGS84, eg. 17.13245"})
     */
    private $zoology_longitud;

    /**
     * @var float The geographic y coordinate, WGS84, eg. 48.188245
     * 
     * @ORM\Column(type="float", options={"comment":"The geographic y coordinate, WGS84, eg. 48.188245"})
     */
    private $zoology_latitud;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $zoology_locality;

    /**
     * @ORM\Column(type="string", length=1)
     * @Assert\Length(max=1)
     * @ORM\ManyToOne(targetEntity="Lkppristupnost")
     * @ORM\JoinColumn(name="zoology_accessibility", referencedColumnName="lkppristupnost_pristupnost", nullable=false)
     */
    private $zoology_accessibility;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $zoology_description;

    /**
     * @ORM\ManyToOne(targetEntity="LkpzoospeciesAves")
     * @ORM\JoinColumn(name="lkpzoospecies_id", referencedColumnName="id")
     */
    private $lkpzoospecies_id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(value=0)
     */
    private $count;

    /**
     * @ORM\ManyToOne(targetEntity="Lkpzoochar")
     * @ORM\JoinColumn(name="lkpzoochar_id", referencedColumnName="id")
     */
    private $lkpzoochar_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $zoology_export;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSfGuardUserId(): ?int
    {
        return $this->sf_guard_user_id;
    }

    public function setSfGuardUserId(int $sf_guard_user_id): self
    {
        $this->sf_guard_user_id = $sf_guard_user_id;

        return $this;
    }

    public function getZoologyDate(): ?\DateTimeInterface
    {
        return $this->zoology_date;
    }

    public function setZoologyDate(\DateTimeInterface $zoology_date): self
    {
        $this->zoology_date = $zoology_date;

        return $this;
    }

    public function getZoologyLongitud(): ?float
    {
        return $this->zoology_longitud;
    }

    public function setZoologyLongitud(float $zoology_longitud): self
    {
        $this->zoology_longitud = $zoology_longitud;

        return $this;
    }

    public function getZoologyLatitud(): ?float
    {
        return $this->zoology_latitud;
    }

    public function setZoologyLatitud(float $zoology_latitud): self
    {
        $this->zoology_latitud = $zoology_latitud;

        return $this;
    }

    public function getZoologyLocality(): ?string
    {
        return $this->zoology_locality;
    }

    public function setZoologyLocality(?string $zoology_locality): self
    {
        $this->zoology_locality = $zoology_locality;

        return $this;
    }

    public function getZoologyAccessibility() //: ?Lkppristupnost
    {
        return $this->zoology_accessibility;
    }

    public function setZoologyAccessibility(?Lkppristupnost $zoology_accessibility): self
    {
        $this->zoology_accessibility = $zoology_accessibility;

        return $this;
    }

    public function getZoologyDescription(): ?string
    {
        return $this->zoology_description;
    }

    public function setZoologyDescription(?string $zoology_description): self
    {
        $this->zoology_description = $zoology_description;

        return $this;
    }

    public function getLkpzoospeciesId() 
    {
        return $this->lkpzoospecies_id;
    }

    public function setLkpzoospeciesId($lkpzoospecies_id): self
    {
        $this->lkpzoospecies_id = $lkpzoospecies_id;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getLkpzoocharId() //: ?int
    {
        return $this->lkpzoochar_id;
    }

    public function setLkpzoocharId($lkpzoochar_id): self
    {
        $this->lkpzoochar_id = $lkpzoochar_id;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getZoologyExport(): ?string
    {
        return $this->zoology_export;
    }

    public function setZoologyExport(?string $zoology_export): self
    {
        $this->zoology_export = $zoology_export;

        return $this;
    }


}
