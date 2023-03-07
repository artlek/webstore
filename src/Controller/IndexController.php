<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        if($this->isGranted('ROLE_VENDOR'))
        {
            return $this->redirectToRoute('app_vendor_dashboard');
        }
        elseif($this->isGranted('ROLE_CLIENT'))
        {
            return $this->redirectToRoute('app_product_list');
        }
        else
        {
            return $this->redirectToRoute('app_product_list');
        }
    }

    #[Route('/manual', name: 'app_manual')]
    public function manual(): Response
    {
        return $this->render('manual.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('about.html.twig', [
            'version' => $_ENV["APP_VERSION"],
        ]);
    }
}
