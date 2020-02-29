<?php


namespace App\Service;


use App\Entity\Persona;
use App\Entity\User;
use App\Model\PersonaModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class PersonaService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function createPersona(PersonaModel $personaModel, User $user)
    {
        if (null === $user) {
            throw new UnsupportedUserException();
        }

        $persona = new Persona();

        return $persona
            ->setName($personaModel->getName())
            ->setType(Persona::TYPE_PC)
            ->setUser($user);
    }

    /**
     * @param PersonaModel $rolePlayModel
     * @param Persona $rolePlay
     * @return Persona
     */
    public function editPersona(PersonaModel $personaModel, Persona $persona)
    {
        return $persona
            ->setAge($personaModel->getAge())
            ->setName($personaModel->getName())
            ->setBio($personaModel->getBio())
        ;
    }
}