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
            new TwigFunction('personaJoinedGame', [$this, 'didPersonaJoinGame'])
        ];
    }

    /**
     * @param Game $game
     * @return bool
     */
    public function isPersonaInPendingGame(Game $game)
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
}