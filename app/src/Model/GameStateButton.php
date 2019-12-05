<?php


namespace App\Model;


class GameStateButton
{
    private $currentGameState;

    private $buttonLabel;

    private $buttonUrl;

    /**
     * GameStateButton constructor.
     * @param string $buttonLabel
     * @param string $currentGameState
     * @param string $buttonUrl
     */
    public function __construct($buttonLabel, $currentGameState, $buttonUrl)
    {
        $this->currentGameState = $currentGameState;
        $this->buttonLabel = $buttonLabel;
        $this->buttonUrl = $buttonUrl;
    }

    /**
     * @return string
     */
    public function getCurrentGameState()
    {
        return $this->currentGameState;
    }

    /**
     * @return string
     */
    public function getButtonLabel()
    {
        return $this->buttonLabel;
    }

    /**
     * @return string
     */
    public function getButtonUrl()
    {
        return $this->buttonUrl;
    }
}