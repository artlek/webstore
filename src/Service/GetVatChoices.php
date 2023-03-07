<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\VatRate;

class GetVatChoices
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    // Gets table of vat rates choices to build a form
    public function getVatChoices() : array
    {
        $vat = new VatRate;
        $vatsRepo = $this->doctrine->getRepository(VatRate::class);
        $vats = $vatsRepo->findAll();
        $vatChoices = [];
        for ($i = 0; $i < count($vats); $i++)
        {
            $vatChoices += [$vats[$i]->getVatRate() => $vats[$i]->getVatRate()];
        }
        return $vatChoices;
    }

}