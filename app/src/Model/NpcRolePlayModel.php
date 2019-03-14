<?php


namespace App\Model;


use App\Entity\GameNpc;
use App\Entity\NpcRolePlay;

class NpcRolePlayModel
{
    /**
     * @var GameNpc
     */
    private $npc;

    /**
     * @var string
     */
    private $content;

    /**
     * @param NpcRolePlay $npcRolePlay
     * @return NpcRolePlayModel
     */
    public static function createFromEventRolePlay(NpcRolePlay $npcRolePlay)
    {
        $eventRolePlayModel = new self();

        return $eventRolePlayModel
            ->setContent($npcRolePlay->getContent())
            ->setNpc($npcRolePlay->getNpc());
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
     * @return GameNpc
     */
    public function getNpc(): ?GameNpc
    {
        return $this->npc;
    }

    /**
     * @param GameNpc $npc
     * @return NpcRolePlayModel
     */
    public function setNpc(GameNpc $npc): NpcRolePlayModel
    {
        $this->npc = $npc;
        return $this;
    }
}