<?php

namespace App\Entity;

use App\Repository\MedecinRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedecinRepository::class)]
class Medecin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dns = null;

    #[ORM\ManyToOne(inversedBy: 'medecins')]
    private ?Hospital $hospitals = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDns(): ?\DateTimeInterface
    {
        return $this->dns;
    }

    public function setDns(\DateTimeInterface $dns): static
    {
        $this->dns = $dns;

        return $this;
    }

    public function getHospitals(): ?Hospital
    {
        return $this->hospitals;
    }

    public function setHospitals(?Hospital $hospitals): static
    {
        $this->hospitals = $hospitals;

        return $this;
    }
}
