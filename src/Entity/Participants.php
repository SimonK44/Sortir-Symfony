<?php

namespace App\Entity;

use App\Repository\ParticipantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticipantsRepository::class)]
#[UniqueEntity(fields: ['pseudo', 'mail'])]
class Participants
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30, unique: true)]
    #[Assert\NotBlank]
    private ?string $pseudo = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    private ?string $prenom = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 20, unique: true)]
    #[Assert\NotBlank]
    private ?string $mail = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    private ?string $motDePasse = null;

    #[ORM\Column]
    private ?bool $administrateur = null;

    #[ORM\Column]
    private ?bool $actif = null;

    /**
     * @var Collection<int, Sorties>
     */
    #[ORM\OneToMany(targetEntity: Sorties::class, mappedBy: 'organisateur')]
    private Collection $sortiesOrganisees;

    /**
     * @var Collection<int, Sorties>
     */
    #[ORM\ManyToMany(targetEntity: Sorties::class, inversedBy: 'participants')]
    private Collection $sortiesInscrites;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sites $site = null;

    public function __construct()
    {
        $this->sortiesOrganisees = new ArrayCollection();
        $this->sortiesInscrites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function isAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): static
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection<int, Sorties>
     */
    public function getSortiesOrganisees(): Collection
    {
        return $this->sortiesOrganisees;
    }

    public function addSorty(Sorties $sorty): static
    {
        if (!$this->sortiesOrganisees->contains($sorty)) {
            $this->sortiesOrganisees->add($sorty);
            $sorty->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSorty(Sorties $sorty): static
    {
        if ($this->sortiesOrganisees->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getOrganisateur() === $this) {
                $sorty->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sorties>
     */
    public function getSortiesInscrites(): Collection
    {
        return $this->sortiesInscrites;
    }

    public function addInscript(Sorties $sortiesInscrites): static
    {
        if (!$this->sortiesInscrites->contains($sortiesInscrites)) {
            $this->sortiesInscrites->add($sortiesInscrites);
        }

        return $this;
    }

    public function removeInscript(Sorties $sortiesInscrites): static
    {
        $this->sortiesInscrites->removeElement($sortiesInscrites);

        return $this;
    }

    public function getSite(): ?Sites
    {
        return $this->site;
    }

    public function setSite(?Sites $site): static
    {
        $this->site = $site;

        return $this;
    }
}
