<?php

namespace App\Model;

use App\Entity\Location;

class GameModel
{
    /** @var string */
    private $title;

    /** @var Location */
    private $location;

    /** @var string */
    private $description;

    /** @var string */
    private $story;

    /** @var int */
    private $maxPlayingPersonas;

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return GameModel
     */
    public function setTitle(string $title): GameModel
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Location
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param Location $location
     * @return GameModel
     */
    public function setLocation(Location $location): GameModel
    {
        $this->location = $location;
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
     * @return GameModel
     */
    public function setDescription(string $description): GameModel
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getStory(): ?string
    {
        return $this->story;
    }

    /**
     * @param string $story
     * @return GameModel
     */
    public function setStory(string $story): GameModel
    {
        $this->story = $story;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxPlayingPersonas(): ?int
    {
        return $this->maxPlayingPersonas;
    }

    /**
     * @param int $maxPlayingPersonas
     * @return GameModel
     */
    public function setMaxPlayingPersonas(int $maxPlayingPersonas): GameModel
    {
        $this->maxPlayingPersonas = $maxPlayingPersonas;
        return $this;
    }
}