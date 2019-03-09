<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Persona;
use App\Entity\User;
use App\Model\GameModel;
use App\Validator\Constraints\JoiningGame;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;
use Symfony\Component\Workflow\Exception\TransitionException;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\TransitionBlocker;

class GameService
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var Registry */
    private $workflows;

    /** @var TranslatorInterface */
    private $translator;

    /** @var FlashBagInterface */
    private $flashBag;

    /** @var ValidatorInterface */
    private $validator;

    /**
     * GameService constructor.
     * @param EntityManagerInterface $em
     * @param Registry $workflows
     * @param TranslatorInterface $translator
     * @param FlashBagInterface $flashBag
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $em,
        Registry $workflows,
        TranslatorInterface $translator,
        FlashBagInterface $flashBag,
        ValidatorInterface $validator
    ){
        $this->em = $em;
        $this->workflows = $workflows;
        $this->translator = $translator;
        $this->flashBag = $flashBag;
        $this->validator = $validator;
    }

    /**
     * @param Game $game
     * @param string $transition
     * @return bool
     */
    public function applyTransition(Game $game, string $transition) : bool
    {
        $workflow = $this->workflows->get($game);
        try {
            $workflow->apply($game, $transition);
            $this->em->flush();

            return true;
        } catch (NotEnabledTransitionException $e) {
            $transitionBlockerList = $e->getTransitionBlockerList();
            /** @var TransitionBlocker[] $blockers */
            $blockers = iterator_to_array($transitionBlockerList);
            $this->flashBag->add('danger', $blockers[0]->getMessage());

            return false;
        }
    }

    /**
     * @param GameModel $gameModel
     * @param User $user
     * @return Game
     */
    public function create(GameModel $gameModel, User $user) : Game
    {
        if (null === $user) {
            throw new UnsupportedUserException();
        }

        $newGame = new Game();

        return $newGame
            ->setLocation($gameModel->getLocation())
            ->setTitle($gameModel->getTitle())
            ->setDescription($gameModel->getDescription())
            ->setStory($gameModel->getStory())
            ->setMaxPlayingPersonas($gameModel->getMaxPlayingPersonas())
            ->setGameMaster($user);
    }

    /**
     * @param Game $game
     * @param User $user
     * @return bool
     */
    public function leave(Game $game, User $user) : bool
    {
        foreach ($user->getPersonas() as $persona) {
            if ($game->getPlayingPersonas()->contains($persona)) {
                $game->getPlayingPersonas()->removeElement($persona);
                $this->em->flush();

                return true;
            }

            if ($game->getPendingPersonas()->contains($persona)) {
                $game->getPendingPersonas()->removeElement($persona);
                $this->em->flush();

                return true;
            }
        }

        return false;
    }

    public function join(Game $game, User $user, Persona $persona)
    {
        $errors = $this->validator->validate($game, new JoiningGame());

        if (count($errors) > 0) {
            $this->flashBag->add('danger', (string) $errors);

            return false;
        }

        if (!$persona->getUser()->isSameAs($user)) {
            $this->flashBag->add('danger', $this->translator->trans('game.flash.joined_without_persona'));

            return false;
        }

        $game->addPendingPersona($persona);
        $this->em->flush();

        return true;
    }
}