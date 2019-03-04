<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
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
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $goal;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxPlayingPersonas;

    /**
     * @ORM\ManyToMany(targetEntity="Persona", inversedBy="pendingGames")
     * @ORM\JoinTable(name="games_pendingPersonas")
     */
    private $pendingPersonas;

    /**
     * @ORM\ManyToMany(targetEntity="Persona", inversedBy="games")
     * @ORM\JoinTable(name="games_playingPersonas")
     */
    private $playingPersonas;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gameMaster;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RolePlay", mappedBy="game", orphanRemoval=true)
     */
    private $rolePlays;

    /**
     * @Gedmo\Slug(fields={"title", "id"})
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $slug;

    public function __construct()
    {
        $this->pendingPersonas = new ArrayCollection();
        $this->playingPersonas = new ArrayCollection();
        $this->rolePlays = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

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

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(?string $goal): self
    {
        $this->goal = $goal;

        return $this;
    }

    public function getMaxPlayingPersonas(): ?int
    {
        return $this->maxPlayingPersonas;
    }

    public function setMaxPlayingPersonas(int $maxPlayingPersonas): self
    {
        $this->maxPlayingPersonas = $maxPlayingPersonas;

        return $this;
    }

    /**
     * @return Collection|Persona[]
     */
    public function getPendingPersonas(): Collection
    {
        return $this->pendingPersonas;
    }

    public function addPendingPersona(Persona $persona): self
    {
        if (!$this->pendingPersonas->contains($persona)) {
            $this->pendingPersonas[] = $persona;
        }

        return $this;
    }

    public function removePendingPersona(Persona $persona): self
    {
        if ($this->pendingPersonas->contains($persona)) {
            $this->pendingPersonas->removeElement($persona);
        }

        return $this;
    }

    /**
     * @return Collection|Persona[]
     */
    public function getPlayingPersonas(): Collection
    {
        return $this->playingPersonas;
    }

    public function addPlayingPersona(Persona $persona): self
    {
        if (!$this->playingPersonas->contains($persona)) {
            $this->playingPersonas[] = $persona;
        }

        return $this;
    }

    public function removePlayingPersona(Persona $persona): self
    {
        if ($this->playingPersonas->contains($persona)) {
            $this->playingPersonas->removeElement($persona);
        }

        return $this;
    }

    public function getGameMaster(): ?User
    {
        return $this->gameMaster;
    }

    public function setGameMaster(?User $gameMaster): self
    {
        $this->gameMaster = $gameMaster;

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
            $rolePlay->setGame($this);
        }

        return $this;
    }

    public function removeRolePlay(RolePlay $rolePlay): self
    {
        if ($this->rolePlays->contains($rolePlay)) {
            $this->rolePlays->removeElement($rolePlay);
            // set the owning side to null (unless already changed)
            if ($rolePlay->getGame() === $this) {
                $rolePlay->setGame(null);
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
     * @return Game
     */
    public function setSlug($slug) : self
    {
        $this->slug = $slug;
        return $this;
    }
}
