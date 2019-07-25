<?php


namespace App\Service;


use App\Entity\Event;
use App\Entity\EventRolePlay;
use App\Entity\Game;
use App\Entity\GameEvent;
use App\Entity\GameNpc;
use App\Entity\NpcRolePlay;
use App\Entity\RolePlay;
use App\Entity\User;
use App\Exception\MissingPersonaException;
use App\Model\EventModel;
use App\Model\EventRolePlayModel;
use App\Model\NpcRolePlayModel;
use App\Model\RolePlayModel;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Role\Role;

class RolePlayService
{
    /**
     * @param RolePlayModel $rolePlayModel
     * @param Game $game
     * @param User $user
     * @return RolePlay
     * @throws MissingPersonaException
     */
    public function createRolePlay(RolePlayModel $rolePlayModel, Game $game, User $user)
    {
        $persona = $this->findPersonaInGame($game, $user->getPersonas());
        if (null === $persona) {
            throw new MissingPersonaException('Can\'t create a rolePlay without an associated persona');
        }

        $rolePlay = new RolePlay();

        return $rolePlay
            ->setContent($rolePlayModel->getContent())
            ->setPersona($persona)
            ->setGame($game)
            ->setUser($user);
    }

    /**
     * @param RolePlayModel $eventModel
     * @param Game $game
     * @param User $user
     * @return RolePlay
     * @throws MissingPersonaException
     */
    public function createEvent(RolePlayModel $eventModel, Game $game, User $user)
    {
        $event = new Event();
        $event->setTitle($eventModel->getTitle());

        $rolePlay = new RolePlay();

        return $rolePlay
            ->setContent($eventModel->getContent())
            ->setType(RolePlay::TYPE_EVENT)
            ->setEvent($event)
            ->setGame($game)
            ->setUser($user);
    }

    /**
     * @param NpcRolePlayModel $npcRolePlayModel
     * @param Game $game
     * @param User $user
     * @return NpcRolePlay
     */
    public function createNpcRolePlay(NpcRolePlayModel $npcRolePlayModel, Game $game, User $user) : NpcRolePlay
    {
        $npcRolePlay = new NpcRolePlay();

        return $npcRolePlay
            ->setContent($npcRolePlayModel->getContent())
            ->setNpc($npcRolePlayModel->getNpc())
            ->setGameMaster($user)
            ->setGame($game);
    }

    /**
     * @param RolePlayModel $rolePlayModel
     * @param RolePlay $rolePlay
     * @return RolePlay
     */
    public function editRolePlay(RolePlayModel $rolePlayModel, RolePlay $rolePlay)
    {
        $event = $rolePlay->getEvent();
        if (null !== $event) {
            $event->setTitle($rolePlayModel->getTitle());
        }

        return $rolePlay
            ->setContent($rolePlayModel->getContent())
            ->setEvent($event);
    }

    private function findPersonaInGame(Game $game, Collection $personas)
    {
        foreach ($personas as $persona) {
            if ($game->getPlayingPersonas()->contains($persona)) {
                return $persona;
            }
        }

        return null;
    }
}