<?php

namespace App\Processor;

use App\Entity\User;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProcessor implements ProcessorInterface
{

    /** @var UserPasswordEncoderInterface $passwordEncoder */
    private $passwordEncoder;

    /**
     * HungryUserProcessor constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritDoc
     */
    public function preProcess(string $id, $object): void
    {
        if (!$object instanceof User) {
            return;
        }

        $this->encodePassword($object);
    }

    /**
     * @inheritDoc
     */
    public function postProcess(string $id, $object): void
    {
        if (!$object instanceof User) {
            return;
        }

        $this->encodePassword($object);
    }

    /**
     * @param User $user
     */
    private function encodePassword(User $user)
    {
        if (!$user->getPlainPassword()) {
            return;
        }

        $encoded = $this->passwordEncoder->encodePassword(
            $user,
            $user->getPlainPassword()
        );

        $user->setPassword($encoded);
    }

}