<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CharacterRepository")
 */
class Persona
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="personas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Game", mappedBy="pendingPersonas")
     */
    private $pendingGames;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Game", mappedBy="playingPersonas")
     */
    private $games;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RolePlay", mappedBy="persona")
     */
    private $rolePlays;

    /**
     * @Gedmo\Slug(fields={"name", "id"})
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * Persona constructor.
     */
    public function __construct()
    {
        $this->pendingGames = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->rolePlays = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    /**
     * @return Collection|Game[]
     */
    public function getPendingGames(): Collection
    {
        return $this->pendingGames;
    }

    public function addPendingGame(Game $pendingGame): self
    {
        if (!$this->pendingGames->contains($pendingGame)) {
            $this->pendingGames[] = $pendingGame;
            $pendingGame->addPendingPersona($this);
        }

        return $this;
    }

    public function removePendingGame(Game $pendingGame): self
    {
        if ($this->pendingGames->contains($pendingGame)) {
            $this->pendingGames->removeElement($pendingGame);
            $pendingGame->removePendingPersona($this);
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->addPlayingPersona($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            $game->removePlayingPersona($this);
        }

        return $this;
    }

    /**
     * @return Collection|RolePlay[]
     */
    public function getRolePlays(): Collection
    {
        return $this->rolePlays;
    }

    public function addRolePlay(RolePlay $rolePlay): self
    {
        if (!$this->rolePlays->contains($rolePlay)) {
            $this->rolePlays[] = $rolePlay;
            $rolePlay->setPersona($this);
        }

        return $this;
    }

    public function removeRolePlay(RolePlay $rolePlay): self
    {
        if ($this->rolePlays->contains($rolePlay)) {
            $this->rolePlays->removeElement($rolePlay);
            // set the owning side to null (unless already changed)
            if ($rolePlay->getPersona() === $this) {
                $rolePlay->setPersona(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug() : string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Persona
     */
    public function setSlug($slug) : self
    {
        $this->slug = $slug;
        return $this;
    }
}
