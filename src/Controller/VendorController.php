<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\Client;
use Doctrine\Persistence\ManagerRegistry;

class VendorController extends AbstractController
{
    #[Route('/vendor/dashboard', name: 'app_vendor_dashboard')]
    public function showDashboard(ManagerRegistry $doctrine): Response
    {
        $productsRepo = $doctrine->getRepository(Product::class);
        $ordersRepo = $doctrine->getRepository(Order::class);
        
        return $this->render('vendor_dashboard.html.twig', [
            'totalProducts' => count($productsRepo->findAll()),
            'totalOrders' =>count($ordersRepo->findAll()),
            'newOrders' => count($ordersRepo->findBy(['status' => 'new']))
        ]);
    }

    #[Route('/vendor/profile', name: 'app_vendor_profile')]
    public function showProfile(ManagerRegistry $doctrine) : Response
    {
        $vendor = $doctrine->getRepository(Client::class);
        $user = $this->getUser();
        return $this->render('vendor_profile.html.twig', [
            'vendor' => $vendor->findOneBy(['email' => $user->getEmail()])
        ]);
    }
}
