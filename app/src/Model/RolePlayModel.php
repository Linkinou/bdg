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
     * @var string
     */
    private $title;

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
    public function getTitle(): string
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
}