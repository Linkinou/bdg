<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameCategoryRepository")
 */
class GameCategory
{
    const TYPE_CATEGORY = 'category';
    const TYPE_FORUM = 'forum';
    const TYPE_LINK = 'link';

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type = self::TYPE_CATEGORY;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GameCategory", inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GameCategory", mappedBy="parent")
     */
    private $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
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

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addGameCategory(self $gameCategory): self
    {
        if (!$this->children->contains($gameCategory)) {
            $this->children[] = $gameCategory;
            $gameCategory->setParent($this);
        }

        return $this;
    }

    public function removeGameCategory(self $gameCategory): self
    {
        if ($this->children->contains($gameCategory)) {
            $this->children->removeElement($gameCategory);
            // set the owning side to null (unless already changed)
            if ($gameCategory->getParent() === $this) {
                $gameCategory->setParent(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
}
