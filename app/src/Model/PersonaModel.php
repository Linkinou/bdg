<?php


namespace App\Model;


use App\Entity\Persona;

class PersonaModel
{
    /**
     * @var string
     */
    private $name;

    public static function createFromPersona(Persona $persona)
    {
        $personaModel = new self();

        return $personaModel
            ->setName($persona->getName());
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
}