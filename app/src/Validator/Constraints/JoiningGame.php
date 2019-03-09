<?php


namespace App\Validator\Constraints;


use App\Entity\Game;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class JoiningGame extends Constraint
{
    public $alreadyInPendingListMessage = 'joining_game.joined_pending_group';
    public $alreadyInPlayingListGameMessage = 'joining_game.joined_playing_group';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}