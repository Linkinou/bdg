<?php

namespace App\Twig;

use App\Entity\Game;
use App\Entity\Persona;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BdgExtension extends AbstractExtension
{
    /**
     * @var Security
     */
    private $security;

    /**
     * BdgExtension constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('personaInPendingGame', [$this, 'isPersonaInPendingGame']),
            new TwigFunction('anyPersonaInPendingGame', [$this, 'isAnyPersonaInPendingGame']),
            new TwigFunction('userPersonaInPendingGame', [$this, 'isUserPersonaInPendingGame']),
            new TwigFunction('personaJoinedGame', [$this, 'didPersonaJoinGame']),
            new TwigFunction('isGameMaster', [$this, 'isGameMaster'])
        ];
    }

    /**
     * @param Game $game
     * @return bool
     */
    public function isAnyPersonaInPendingGame(Game $game)
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user) {
            return false;
        }

        if (empty(array_intersect($user->getPersonas()->toArray(), $game->getPendingPersonas()->toArray()))) {
            return false;
        }

        return true;
    }

    /**
     * @param Game $game
     * @return bool
     */
    public function isPersonaInPendingGame(Game $game, Persona $persona)
    {
        if (!$game->getPendingPersonas()->contains($persona)) {
            return false;
        }

        return true;
    }

    /**
     * @param Game $game
     * @return bool
     */
    public function isUserPersonaInPendingGame(Game $game, Persona $persona)
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user) {
            return false;
        }

        if ($persona->getUser() !== $user) {
            return false;
        }

        if (!$game->getPendingPersonas()->contains($persona)) {
            return false;
        }

        return true;
    }

    /**
     * @param Game $game
     * @return bool
     */
    public function didPersonaJoinGame(Game $game)
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user) {
            return false;
        }

        if (empty(array_intersect($user->getPersonas()->toArray(), $game->getPlayingPersonas()->toArray()))) {
            return false;
        }

        return true;
    }

    /**
     * @param Game $game
     * @return bool
     */
    public function isGameMaster(Game $game)
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user) {
            return false;
        }

        if (!$game->isGameMaster($user)) {
            return false;
        }

        return true;
    }
}