<?php


namespace App\Model;


use App\Entity\RolePlay;

class RolePlayModel
{
    /**
     * @var string
     */
    private $content;

    /**
     * @param RolePlay $rolePlay
     * @return RolePlayModel
     */
    public static function createFromRolePlay(RolePlay $rolePlay)
    {
        $rolePlayModel = new self();

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
}