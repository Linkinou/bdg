<?php


namespace App\Model;


use App\Entity\Persona;
use App\Entity\RolePlay;

class NpcRolePlayModel
{
    /**
     * @var Persona
     */
    private $npc;

    /**
     * @var string
     */
    private $content;

    /**
     * @param RolePlay $npcRolePlay
     * @return NpcRolePlayModel
     */
    public static function createFromEventRolePlay(RolePlay $npcRolePlay)
    {
        $npcRolePlayModel = new self();

        return $npcRolePlayModel
            ->setContent($npcRolePlay->getContent())
            ->setNpc($npcRolePlay->getPersona());
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
     * @return NpcRolePlayModel
     */
    public function setContent(string $content): NpcRolePlayModel
    {
        $this->content = $content;
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
     * @return NpcRolePlayModel
     */
    public function setNpc(Persona $npc): NpcRolePlayModel
    {
        $this->npc = $npc;
        return $this;
    }
}