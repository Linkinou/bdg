<?php


namespace App\Model;


use App\Entity\Persona;

class PersonaModel
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $age;

    /**
     * @var string
     */
    private $bio;

    public static function createFromPersona(Persona $persona)
    {
        $personaModel = new self();

        return $personaModel
            ->setName($persona->getName())
            ->setAge($persona->getAge())
            ->setBio($persona->getBio())
        ;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return PersonaModel
     */
    public function setName(string $name): PersonaModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getAge(): ?int
    {
        return $this->age;
    }

    /**
     * @param int $age
     * @return PersonaModel
     */
    public function setAge(int $age): PersonaModel
    {
        $this->age = $age;
        return $this;
    }

    /**
     * @return string
     */
    public function getBio(): ?string
    {
        return $this->bio;
    }

    /**
     * @param string $bio
     * @return PersonaModel
     */
    public function setBio(string $bio): PersonaModel
    {
        $this->bio = $bio;
        return $this;
    }
}