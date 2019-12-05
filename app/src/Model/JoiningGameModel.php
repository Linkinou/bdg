<?php


namespace App\Model;


use App\Entity\Persona;

class JoiningGameModel
{
    /** @var Persona */
    private $persona;

    /**
     * @return Persona
     */
    public function getPersona(): ?Persona
    {
        return $this->persona;
    }

    /**
     * @param Persona $persona
     * @return JoiningGameModel
     */
    public function setPersona(Persona $persona): JoiningGameModel
    {
        $this->persona = $persona;
        return $this;
    }
}