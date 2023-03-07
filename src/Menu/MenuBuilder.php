<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use App\Service\CartManager;

class MenuBuilder
{
    private $factory;
    private $cartLabel;

    public function __construct(FactoryInterface $factory, CartManager $cartManager)
    {
        $this->factory = $factory;
        if(is_null($cartManager->getCalculate()))
        {
            $this->cartLabel = 'Cart';
        }
        else
        {
            $this->cartLabel = 'Cart' . ' (' . $cartManager->getCalculate()['items'] . ')';
        }
    }

    public function createClientMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Home', ['route' => 'app_index', 'attributes' => ['class' => 'main-menu']]);
        $menu['Home']->setLinkAttribute('class', 'main-menu');
        $menu->addChild('Products', ['route' => 'app_product_list', 'attributes' => ['class' => 'main-menu']]);
        $menu['Products']->setLinkAttribute('class', 'main-menu');
        $menu->addChild('Orders', ['route' => 'app_client_orders', 'attributes' => ['class' => 'main-menu']]);
        $menu['Orders']->setLinkAttribute('class', 'main-menu');
        $menu->addChild($this->cartLabel, ['route' => 'app_cart', 'attributes' => ['class' => 'main-menu']]);
        $menu[$this->cartLabel]->setLinkAttribute('class', 'main-menu');

        return $menu;
    }

    public function createVendortMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Home', ['route' => 'app_index', 'attributes' => ['class' => 'main-menu']]);
        $menu['Home']->setLinkAttribute('class', 'main-menu');
        $menu->addChild('Products', ['route' => 'app_vendor_product_list', 'attributes' => ['class' => 'main-menu']]);
        $menu['Products']->setLinkAttribute('class', 'main-menu');
        $menu->addChild('Orders', ['route' => 'app_vendor_orders', 'attributes' => ['class' => 'main-menu']]);
        $menu['Orders']->setLinkAttribute('class', 'main-menu');
        $menu->addChild('Vat rates', ['route' => 'app_vat', 'attributes' => ['class' => 'main-menu']]);
        $menu['Vat rates']->setLinkAttribute('class', 'main-menu');
        $menu->addChild('Units', ['route' => 'app_unit', 'attributes' => ['class' => 'main-menu']]);
        $menu['Units']->setLinkAttribute('class', 'main-menu');

        return $menu;
    }

    public function createGuestMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Home', ['route' => 'app_index', 'attributes' => ['class' => 'main-menu']]);
        $menu['Home']->setLinkAttribute('class', 'main-menu');
        $menu->addChild('Products', ['route' => 'app_product_list', 'attributes' => ['class' => 'main-menu']]);
        $menu['Products']->setLinkAttribute('class', 'main-menu');

        return $menu;
    }

    public function createLoginMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Login', ['route' => 'app_login', 'attributes' => ['class' => 'main-menu']]);
        $menu['Login']->setLinkAttribute('class', 'main-menu');
        $menu->addChild('Register', ['route' => 'app_register', 'attributes' => ['class' => 'main-menu']]);
        $menu['Register']->setLinkAttribute('class', 'main-menu');

        return $menu;
    }

    public function createFooterMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('About', ['route' => 'app_about', 'attributes' => ['class' => 'footer-menu']]);
        $menu['About']->setLinkAttribute('class', 'footer-menu');
        $menu->addChild('User manual', ['route' => 'app_manual', 'attributes' => ['class' => 'footer-menu']]);
        $menu['User manual']->setLinkAttribute('class', 'footer-menu');

        return $menu;
    }
}