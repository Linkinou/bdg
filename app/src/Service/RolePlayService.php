<?php


namespace App\Service;


use App\Entity\Game;
use App\Entity\RolePlay;
use App\Entity\User;
use App\Exception\MissingPersonaException;
use App\Model\RolePlayModel;
use Doctrine\Common\Collections\Collection;

class RolePlayService
{
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
            ->setGame($game);

    }

    /**
     * @param RolePlayModel $rolePlayModel
     * @param RolePlay $rolePlay
     * @return RolePlay
     */
    public function editRolePlay(RolePlayModel $rolePlayModel, RolePlay $rolePlay)
    {
        return $rolePlay
            ->setContent($rolePlayModel->getContent());
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