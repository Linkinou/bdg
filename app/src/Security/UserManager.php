<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserManager constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $entityManager;
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $plainPassword
     * @param array $roles
     * @return User
     */
    public function createUser(
        string $username,
        string $email,
        string $plainPassword,
        array $roles
    ) : User {
        $user = new User();
        $password = $this->passwordEncoder->encodePassword($user, $plainPassword);

        $user
            ->setPassword($password)
            ->setEmail($email)
            ->setUsername($username)
            ->setRoles($roles)
        ;

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}