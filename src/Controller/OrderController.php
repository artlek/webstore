<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ChangeOrderStatusFormType;

class OrderController extends AbstractController
{
    #[Route('/vendor/orders', name: 'app_vendor_orders')]
    public function showVendorOrders(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Order::class);
        $orders = $repository->findAll();
        return $this->render('order_list_vendor.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/vendor/order/{id}', name: 'app_vendor_order')]
    public function showVendorOrderDetails(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $repository = $doctrine->getRepository(Order::class);
        $order = $repository->findOneBy(['id' => $id]);
        $form = $this->createForm(ChangeOrderStatusFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() AND $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $status = $form->getData();
            $order->setStatus($status['status']);
            $entityManager->flush();
            
            $this->addFlash(
                'positiveInfo',
                'Status has been changed.'
            );
        }

        return $this->render('order_vendor.html.twig', [
            'order' => $order,
            'form' => $form->createView()
        ]);
    }

    #[Route('/client/orders', name: 'app_client_orders')]
    public function showClientOrders(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Order::class);
        $orders = $repository->findBy(
            ['email' => $this->getUser()->getEmail()],
        );
        return $this->render('order_list_client.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/client/order/{id}', name: 'app_client_order')]
    public function showClientOrderDetails(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $repository = $doctrine->getRepository(Order::class);
        $order = $repository->findOneBy(['id' => $id]);
        $form = $this->createForm(ChangeOrderStatusFormType::class);
        $form->handleRequest($request);

        return $this->render('order_client.html.twig', [
            'order' => $order
        ]);
    }
}
