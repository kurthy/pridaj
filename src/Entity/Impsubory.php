<?php

namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImpsuboryRepository")
 */
class Impsubory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $sf_guard_user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $impsubor;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $notice;

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

    public function getImpsubor(): ?string
    {
        return $this->impsubor;
    }

    public function setImpsubor(string $impsubor): self
    {
        $this->impsubor = $impsubor;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getNotice(): ?string
    {
        return $this->notice;
    }

    public function setNotice(?string $notice): self
    {
        $this->notice = $notice;

        return $this;
    }
}
