<?php


namespace App\Validator\Constraints;


use App\Entity\Game;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class JoiningGameValidator extends ConstraintValidator
{
    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * @var Security
     */
    private $security;

    /**
     * HasEnoughPersonasValidator constructor.
     * @param FlashBagInterface $flashBag
     * @param Security $security
     */
    public function __construct(FlashBagInterface $flashBag, Security $security)
    {
        $this->flashBag = $flashBag;
        $this->security = $security;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof JoiningGame) {
            throw new UnexpectedTypeException($constraint, JoiningGame::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!($value instanceof Game)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new \UnexpectedValueException($value, 'Game');
        }

        /** @var User $user */
        $user = $this->security->getUser();
        if (!empty(array_intersect($value->getPendingPersonas()->toArray(), $user->getPersonas()->toArray()))) {
            $this->context->buildViolation($constraint->alreadyInPendingListMessage)->addViolation();
        }

        if (!empty(array_intersect($value->getPlayingPersonas()->toArray(), $user->getPersonas()->toArray()))) {
            $this->context->buildViolation($constraint->alreadyInPlayingListGameMessage)->addViolation();
        }
    }
}