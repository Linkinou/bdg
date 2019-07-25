<?php


namespace App\Model;


use App\Entity\Game;
use App\Entity\User;

class GameRoomModel
{
    /**
     * @var int
     */
    private $maxPlayingPersonas;

    /**
     * @var boolean
     */
    private $isCurrentGameMaster;

    /**
     * @var string
     */
    private $currentState;

    /**
     * @var array
     */
    private $currentPlayerPersonas;

    /**
     * @var array
     */
    private $urls;

    /**
     * @param Game $game
     * @param User|null $user
     * @param array $urls
     * @return GameRoomModel
     */
    static function createFromGame(Game $game, User $user = null, $urls = []) {
        $gameRoomModel = new self();

        return $gameRoomModel
            ->setMaxPlayingPersonas($game->getMaxPlayingPersonas())
            ->setIsCurrentGameMaster($user !== null ?? $game->isGameMaster($user))
            ->setCurrentState($game->getState())
            ->setCurrentPlayerPersonas($user !== null ?? $user->getPersonas())
            ->setUrls()
        ;
    }

    /**
     * @param int $maxPlayingPersonas
     * @return GameRoomModel
     */
    public function setMaxPlayingPersonas(int $maxPlayingPersonas): GameRoomModel
    {
        $this->maxPlayingPersonas = $maxPlayingPersonas;
        return $this;
    }

    /**
     * @param bool $isCurrentGameMaster
     * @return GameRoomModel
     */
    public function setIsCurrentGameMaster(bool $isCurrentGameMaster): GameRoomModel
    {
        $this->isCurrentGameMaster = $isCurrentGameMaster;
        return $this;
    }

    /**
     * @param string $currentState
     * @return GameRoomModel
     */
    public function setCurrentState(string $currentState): GameRoomModel
    {
        $this->currentState = $currentState;
        return $this;
    }

    /**
     * @param array $currentPlayerPersonas
     * @return GameRoomModel
     */
    public function setCurrentPlayerPersonas(array $currentPlayerPersonas): GameRoomModel
    {
        $this->currentPlayerPersonas = $currentPlayerPersonas;
        return $this;
    }

    /**
     * @param array $urls
     * @return GameRoomModel
     */
    public function setUrls(array $urls): GameRoomModel
    {
        $this->urls = $urls;
        return $this;
    }

    /**
     * @param string $url
     * @return GameRoomModel
     */
    public function addUrl(string $url): GameRoomModel
    {
        $this->urls[] = $url;
        return $this;
    }
}