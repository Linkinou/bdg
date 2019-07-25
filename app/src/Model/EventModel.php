<?php


namespace App\Model;


use App\Entity\RolePlay;

class EventModel
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
     * @return EventModel
     */
    public static function createFromRolePlay(RolePlay $rolePlay)
    {
        $eventModel = new self();

        return $eventModel
            ->setContent($rolePlay->getContent())
            ->setTitle($rolePlay->getEvent()->getTitle());
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
     * @return EventModel
     */
    public function setContent(string $content): EventModel
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
     * @return EventModel
     */
    public function setTitle(string $title): EventModel
    {
        $this->title = $title;
        return $this;
    }
}