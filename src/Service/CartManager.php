<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Order;

class CartManager extends AbstractController
{
    private int $counter;
    private int $count;
    private int $previousQuantity;
    private array $sessionCart;
    private array $cartCalculate;
    private int $productCount;
    private float $value;
    private $session;
    private $entityManager;
    private int $orderNo;

    public function __construct(private RequestStack $requestStack, private ManagerRegistry $doctrine)
    {
        $this->session = $requestStack->getSession();
        $this->entityManager = $doctrine->getManager();
    }

    // checks if adding product (CartProduct $cartProduct) exists in the cart
    public function anyProductExist($cartProduct) : bool
    {
        if($this->session->get('cart'))
        {
            $this->count = count($this->session->get('cart'));
            $this->sessionCart = $this->session->get('cart');
            for($i = 0; $i < $this->count; $i++)
            {
                if($this->sessionCart[$i]['code'] == $cartProduct->getCode() AND $this->sessionCart[$i]['price'] == $cartProduct->getPrice())
                {
                    $this->counter = $i;
                    return true;
                }
            }
        }
        return false;
    }

    // checks if concrete product code (int $productCode) exists in the cart
    public function productExist($productCode) : bool
    {
        if($this->session->get('cart'))
        {
            $this->count = count($this->session->get('cart'));
            $this->sessionCart = $this->session->get('cart');
            for($i = 0; $i < $this->count; $i++)
            {
                if($this->sessionCart[$i]['code'] == $productCode)
                {
                    $this->counter = $i;
                    return true;
                }
            }
        }
        return false;

    }

    // adds product passed from the form to the cart
    public function addToCart($cartProduct)
    {
        $this->sessionCart = array();
        
        if($this->anyProductExist($cartProduct))
        {
            $this->sessionCart = $this->session->get('cart');
            $this->previousQuantity = $this->sessionCart[$this->counter]['quantity'];
            $this->sessionCart[$this->counter]['quantity'] = $cartProduct->getQuantity() + $this->previousQuantity;
            $this->sessionCart[$this->counter]['total'] = $this->sessionCart[$this->counter]['quantity'] * 
            $this->sessionCart[$this->counter]['price'];
            if($this->sessionCart[$this->counter]['quantity'] > 999999)
            {
                $this->sessionCart[$this->counter]['quantity'] = 999999;
            }
            $this->session->set('cart', $this->sessionCart);
        }
        else
        {
            if($this->session->get('cart'))
            {
                if($cartProduct->getQuantity() > 999999)
                {
                    $cartProduct->setQuantity(999999);
                }
                $this->sessionCart = $this->session->get('cart');
                array_push($this->sessionCart, 
                        array(
                        'code' => $cartProduct->getCode(),
                        'name' => $cartProduct->getName(),
                        'price' => $cartProduct->getPrice(),
                        'unit' => $cartProduct->getUnit(),
                        'vatrate' => $cartProduct->getVatrate(),
                        'quantity' => $cartProduct->getQuantity(),
                        'total' => $cartProduct->getTotal(),
                    )
                );
            }
            else
            {
                if($cartProduct->getQuantity() > 999999)
                {
                    $cartProduct->setQuantity(999999);
                }
                $this->sessionCart = array(array(
                    'code' => $cartProduct->getCode(),
                    'name' => $cartProduct->getName(),
                    'price' => $cartProduct->getPrice(),
                    'unit' => $cartProduct->getUnit(),
                    'vatrate' => $cartProduct->getVatrate(),
                    'quantity' => $cartProduct->getQuantity(),
                    'total' => $cartProduct->getTotal(),
                    )
                );
            }
            $this->session->set('cart', $this->sessionCart);
        }
    }

    // deletes product from cart
    public function deleteFromCart($productCode)
    {
        $this->sessionCart = $this->session->get('cart');
        $this->count = count($this->session->get('cart'));
        for($i = 0; $i < $this->count; $i++)
        {
            if($this->sessionCart[$i]['code'] == $productCode)
            {
                $this->counter = $i;
                unset($this->sessionCart[$this->counter]);
                $this->sessionCart = array_values($this->sessionCart);
                $this->session->set('cart', $this->sessionCart);
                return true;
            }
        }
    }

    // shows cart calculation
    public function getCalculate()
    {
        if($this->session->get('cart'))
        {
            $this->sessionCart = $this->session->get('cart');
            $this->count = count($this->sessionCart);
            $this->productCount = 0;
            $this->value = 0;
            for($i = 0; $i < $this->count; $i++)
            {
                $this->productCount += $this->sessionCart[$i]['quantity'];
                $this->value += $this->sessionCart[$i]['total'];
            }
            return array(
                'products' => $this->productCount,
                'items' => $this->count,
                'value' => $this->value,
            );
        }
    }

    // edits product quantity in the cart
    public function editQuantity(int $productCode, int $newQuantity) : bool
    {
        $this->sessionCart = $this->session->get('cart');
        $this->count = count($this->session->get('cart'));
        if($this->session->get('cart') AND $newQuantity > 0 AND $newQuantity < 1000000)
        {
            for($i = 0; $i < $this->count; $i++)
            {
                if($this->sessionCart[$i]['code'] == $productCode)
                {
                    $this->sessionCart[$i]['quantity'] = $newQuantity;
                    $this->sessionCart[$i]['total'] = $newQuantity * $this->sessionCart[$i]['price'];
                    $this->session->set('cart', $this->sessionCart);
                    return true;
                }
            }
            return false;
        }
        elseif($newQuantity == 0)
        {
            if($this->productExist($productCode))
            {
                $this->deleteFromCart($productCode);
                return true;
            }
            return false;
        }
        else
        {
            return false;
        }
    }

    // deletes all products from cart
    public function deleteCart()
    {
        $this->session->remove('cart');
    }

    //saves the cart as order and deletes products from the cart
    public function saveCart() : bool
    {
        if(isset($_POST['save-cart']) AND $this->session->get('cart'))
        {
            $this->sessionCart = $this->session->get('cart');
            $calculate = $this->getCalculate();
            $user = $this->getUser();
            $order = new Order;
            $order->setClient($user->getName() . ' ' . $user->getSurname());
            $order->setEmail($user->getEmail());
            $order->setProducts($this->sessionCart);
            $order->setDatetime(date("Y-m-d H:i:s"));
            $order->setStatus('new');
            $order->setProductCount($calculate['products']);
            $order->setItems($calculate['items']);
            $order->setTotal($calculate['value']);
            $this->entityManager->persist($order);
            $this->entityManager->flush();
            $this->orderNo = $order->getId();
            $this->deleteCart();
            return true;
        }
        return false;
    }

    public function getOrderNo()
    {
        return $this->orderNo;
    }
}