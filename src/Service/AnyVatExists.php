<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\VatRate;

class AnyVatExists
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    // checks if any vat rate is set
    public function anyVatExists() : bool
    {
        $vat = new VatRate;
        $vatsRepo = $this->doctrine->getRepository(VatRate::class);
        if(count($vatsRepo->findAll()) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}