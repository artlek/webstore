<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\Client;
use Doctrine\Persistence\ManagerRegistry;

class ClientController extends AbstractController
{
    #[Route('/client/profile', name: 'app_client_profile')]
    public function showProfile(ManagerRegistry $doctrine) : Response
    {
        $client = $doctrine->getRepository(Client::class);
        $user = $this->getUser();
        return $this->render('client_profile.html.twig', [
            'client' => $client->findOneBy(['email' => $user->getEmail()])
        ]);
    }
}
