<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Client;

class GetCurrency
{
    private ?string $currency;

    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    // shows the set currency
    public function getCurrency() : string
    {
        $repo = $this->doctrine->getRepository(Client::class);
        $vendors = $repo->findAll();
        for($i = 0; $i < count($vendors); $i++)
        {
            if($vendors[$i]->getRoles()['0'] == 'ROLE_VENDOR')
            {
                $this->currency = $vendors[$i]->getCurrency();
            }
        }
        if($this->currency == null)
        {
            return 'no currency set';
        }
        else
        {
            return $this->currency;
        }
    }
}