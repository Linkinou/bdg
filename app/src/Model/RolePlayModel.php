<?php


namespace App\Model;


use App\Entity\Persona;
use App\Entity\RolePlay;

class RolePlayModel
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $title = null;

    /**
     * @var Persona
     */
    private $npc = null;

    /**
     * @param RolePlay $rolePlay
     * @return RolePlayModel
     */
    public static function createFromRolePlay(RolePlay $rolePlay)
    {
        $rolePlayModel = new self();

        if (null !== $rolePlay->getEvent()) {
            $rolePlayModel->setTitle($rolePlay->getEvent()->getTitle());
        }

        if (Persona::TYPE_NPC === $rolePlay->getPersona()->getType()) {
            $rolePlayModel->setNpc($rolePlay->getPersona());
        }

        return $rolePlayModel
            ->setContent($rolePlay->getContent());
    }

    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return RolePlayModel
     */
    public function setContent(string $content): RolePlayModel
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return RolePlayModel
     */
    public function setTitle(string $title): RolePlayModel
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Persona
     */
    public function getNpc(): ?Persona
    {
        return $this->npc;
    }

    /**
     * @param Persona $npc
     * @return RolePlayModel
     */
    public function setNpc(Persona $npc): RolePlayModel
    {
        $this->npc = $npc;
        return $this;
    }
}