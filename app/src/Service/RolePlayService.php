<?php


namespace App\Service;


use App\Entity\Event;
use App\Entity\Game;
use App\Entity\RolePlay;
use App\Entity\User;
use App\Exception\MissingPersonaException;
use App\Model\NpcRolePlayModel;
use App\Model\RolePlayModel;
use Doctrine\Common\Collections\Collection;

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
            ->setType(RolePlay::TYPE_ROLEPLAY)
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
     * @param RolePlayModel $npcRolePlayModel
     * @param Game $game
     * @param User $user
     * @return RolePlay
     */
    public function createNpcRolePlay(RolePlayModel $npcRolePlayModel, Game $game, User $user) : RolePlay
    {
        $npcRolePlay = new RolePlay();

        return $npcRolePlay
            ->setContent($npcRolePlayModel->getContent())
            ->setPersona($npcRolePlayModel->getNpc())
            ->setType(RolePlay::TYPE_NPC_ROLEPLAY)
            ->setUser($user)
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

    /**
     * @param Game $game
     * @param Collection $personas
     * @return mixed|null
     */
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