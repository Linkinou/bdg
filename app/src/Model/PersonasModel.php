<?php


namespace App\Model;


use App\Entity\Persona;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class PersonasModel
{
    /** @var Persona[] */
    private $personas;

    public function __construct()
    {
        $this->personas = new ArrayCollection();
    }

    /**
     * @return Persona[]
     */
    public function getPersonas(): ?Collection
    {
        return $this->personas;
    }

    /**
     * @param Persona[] $personas
     * @return PersonasModel
     */
    public function setPersonas(Collection $personas): PersonasModel
    {
        $this->personas = $personas;
        return $this;
    }
}