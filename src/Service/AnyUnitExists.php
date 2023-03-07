<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Unit;

class AnyUnitExists
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    // checks if any unit of measure is set
    public function anyUnitExists() : bool
    {
        $unit = new Unit;
        $unitsRepo = $this->doctrine->getRepository(Unit::class);
        if(count($unitsRepo->findAll()) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}