<?php

namespace App\EventListener;

use App\Entity\Game;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\TransitionBlocker;

class GameLifeGuard implements EventSubscriberInterface
{

    /** @var Security */
    private $security;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * GameStartListener constructor.
     * @param Security $security
     * @param TranslatorInterface $translator
     */
    public function __construct(Security $security, TranslatorInterface $translator)
    {
        $this->security = $security;
        $this->translator = $translator;
    }

    public function guardStart(GuardEvent $event)
    {
        /** @var Game $game */
        $game = $event->getSubject();
        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user || !$game->isGameMaster($user)) {
            $event->addTransitionBlocker(new TransitionBlocker(
                $this->translator->trans('workflow.guard.start_game.not_gamemaster'),
                'is_not_mj'
            ));
        }

        if ($game->getPlayingPersonas()->count() < Game::MINIMUM_PERSONAS_REQUIRED) {
            $event->addTransitionBlocker(new TransitionBlocker(
                $this->translator->trans('workflow.guard.start_game.not_enough_players', ['%min%' => Game::MINIMUM_PERSONAS_REQUIRED]),
                'not_enough_players'
            ));
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.game_life.guard.start' => ['guardStart']
        ];
    }

}