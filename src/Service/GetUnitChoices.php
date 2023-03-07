<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Unit;

class GetUnitChoices
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    // Gets table of unit of measure choices to build a form
    public function getUnitChoices() : array
    {
        $unit = new Unit;
        $unitsRepo = $this->doctrine->getRepository(Unit::class);
        $unitChoices = [];
        $units = $unitsRepo->findAll();
        for ($i = 0; $i < count($units); $i++)
        {
            $unitChoices += [$units[$i]->getShortName() => $units[$i]->getShortName()];
        }
        return $unitChoices;
    }
}