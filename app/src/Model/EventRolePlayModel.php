<?php


namespace App\Model;


use App\Entity\EventRolePlay;

class EventRolePlayModel
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $content;

    /**
     * @param EventRolePlay $eventRolePlay
     * @return EventRolePlayModel
     */
    public static function createFromEventRolePlay(EventRolePlay $eventRolePlay)
    {
        $eventRolePlayModel = new self();

        return $eventRolePlayModel
            ->setContent($eventRolePlay->getContent())
            ->setTitle($eventRolePlay->getTitle())
            ->setDescription($eventRolePlay->getDescription());
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
     * @return EventRolePlayModel
     */
    public function setContent(string $content): EventRolePlayModel
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
     * @return EventRolePlayModel
     */
    public function setTitle(string $title): EventRolePlayModel
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return EventRolePlayModel
     */
    public function setDescription(string $description): EventRolePlayModel
    {
        $this->description = $description;
        return $this;
    }
}