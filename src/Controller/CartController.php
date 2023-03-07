<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\CartManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\CartProduct;
use App\Entity\Order;

class CartController extends AbstractController
{
    private $requestStack;
    private $sessionCart;
    private $productCode;

    #[Route('/client/cart', name: 'app_cart')]
    public function showCart(RequestStack $requestStack, CartManager $cartManager): Response
    {
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getSession();
        if($session->get('cart'))
        {
            return $this->render('cart.html.twig', [
                'cart' => $session->get('cart'),
                'calculate' => $cartManager->getCalculate(),
            ]);
        }
        else
        {
            return $this->render('cart.html.twig');
        }
    }

    #[Route('/client/cart/delete-product', name: 'app_delete_cart_product')]
    public function deleteProduct(CartManager $cartManager) : Response
    {
        if(isset($_POST['code']) AND isset($_POST['name']))
        {
            if($cartManager->deleteFromCart($_POST['code']))
            {
                $this->addFlash(
                    'positiveInfo',
                    'Product (' . $_POST['name'] . ') was deleted from cart.'
                );
            }

            return $this->redirectToRoute('app_cart');
        }
        else
        {
            return $this->redirectToRoute('app_cart');
        }
    }

    #[Route('/client/cart/add', name: 'app_add_to_cart')]
    public function addProduct(ManagerRegistry $doctrine, Request $request, CartManager $cartManager) : Response
    {   
        $this->denyAccessUnlessGranted('ROLE_CLIENT', null, 'User tried to access a page without having ROLE_CLIENT');
        if(isset($_POST['quantity']) AND is_numeric($_POST['quantity']) AND $_POST['quantity'] > 0 AND $_POST['quantity'] < 999999)
        {
            $cartProduct = new CartProduct;
            $cartManager->addToCart($cartProduct);
            $this->addFlash(
                'positiveInfo',
                'Product (' . $cartProduct->getName() . ') was added to cart.'
            );
            return $this->redirect('/product/' . $cartProduct->getCode());
        }
        else
        {
            return $this->redirectToRoute('app_product_list');
        }
    }

    #[Route('/client/cart/edit', name: 'app_edit_cart_product')]
    public function editProduct(CartManager $cartManager) : Response
    {
        if(is_numeric($_POST['new-quantity']) AND $cartManager->editQuantity($_POST['code'], $_POST['new-quantity']))
        {
            $this->addFlash(
                'positiveInfo',
                'Quantity of product (' . $_POST['name'] . ') has been updated.'
            );
        }
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/client/cart/delete', name: 'app_delete_cart')]
    public function deleteCart(CartManager $cartManager, RequestStack $requestStack) : Response
    {
        $this->requestStack = $requestStack;
        $session = $this->requestStack->getSession();
        if(isset($_POST['delete-cart']) AND $session->get('cart'))
        {
            $cartManager->deleteCart();
        }
        $this->addFlash(
            'positiveInfo',
            'All products have been removed from the cart.'
        );
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/client/cart/save', name: 'app_save_cart')]
    public function saveCart(CartManager $cartManager) : Response
    {
        if($cartManager->saveCart() == true)
        {
            $this->addFlash(
                'positiveInfo',
                'Order no ' . $cartManager->getOrderNo() . ' has been saved.'
            );
        }
        return $this->redirectToRoute('app_cart');
    }
}
