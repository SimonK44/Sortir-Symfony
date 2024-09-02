<?php

namespace App\Entity;

use App\Repository\LieuxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LieuxRepository::class)]
#[ORM\UniqueConstraint(columns: ['nomLieu', 'ville'])]
#[UniqueEntity(fields: ['nomLieu', 'ville'], message: 'Un lieu avec ce nom existe déja dans cette ville')]
class Lieux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: 'Il faut un nom à ton lieu !')]
    private ?string $nomLieu = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\NotBlank(message: 'Rue obligatoire')]
    private ?string $rue = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: 'Latitude obligatoire')]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: 'Latitude obligatoire')]
    private ?float $longitude = null;

    #[ORM\ManyToOne(inversedBy: 'lieux')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Ville obligatoire')]
    private ?Villes $ville = null;

    /**
     * @var Collection<int, Sorties>
     */
    #[ORM\OneToMany(targetEntity: Sorties::class, mappedBy: 'lieux')]
    private Collection $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNomLieu(): ?string
    {
        return $this->nomLieu;
    }

    public function setNomLieu(string $nomLieu): static
    {
        $this->nomLieu = $nomLieu;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getVille(): ?Villes
    {
        return $this->ville;
    }

    public function setVille(?Villes $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, Sorties>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sorties $sorty): static
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties->add($sorty);
            $sorty->setLieux($this);
        }

        return $this;
    }

    public function removeSorty(Sorties $sorty): static
    {
        if ($this->sorties->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getLieux() === $this) {
                $sorty->setLieux(null);
            }
        }

        return $this;
    }
}
