<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Unit;
use App\Entity\VatRate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product->setCode(mt_rand(1000, 9999));
        $product->setName('HDMI Cable HDMI 2.1');
        $product->setPrice(mt_rand(20.00, 850.00));
        $product->setUnit('pcs.');
        $product->setVatRate(23);
        $product->setDescription('An example of product description. An example of product description. An example of product description.');
        $product->setHasPhoto(false);
        $product->setCurrency('EUR');
        $manager->persist($product);

        $product = new Product();
        $product->setCode(mt_rand(1000, 9999));
        $product->setName('USB C Charger 33W Type C');
        $product->setPrice(mt_rand(20.00, 850.00));
        $product->setUnit('pcs.');
        $product->setVatRate(23);
        $product->setDescription('An example of product description. An example of product description. An example of product description.');
        $product->setHasPhoto(false);
        $product->setCurrency('EUR');
        $manager->persist($product);

        $product = new Product();
        $product->setCode(mt_rand(1000, 9999));
        $product->setName('Outdoor Sport Men Watch Men 5Bar Waterproof');
        $product->setPrice(mt_rand(20.00, 850.00));
        $product->setUnit('pcs.');
        $product->setVatRate(23);
        $product->setDescription('An example of product description. An example of product description. An example of product description.');
        $product->setHasPhoto(false);
        $product->setCurrency('EUR');
        $manager->persist($product);

        
        $product = new Product();
        $product->setCode(mt_rand(1000, 9999));
        $product->setName('Led Desk Lamp - Eye Protection - DC5V USB Chargeable');
        $product->setPrice(mt_rand(20.00, 850.00));
        $product->setUnit('pcs.');
        $product->setVatRate(23);
        $product->setDescription('An example of product description. An example of product description. An example of product description.');
        $product->setHasPhoto(false);
        $product->setCurrency('EUR');
        $manager->persist($product);

        $product = new Product();
        $product->setCode(mt_rand(1000, 9999));
        $product->setName('Rubber Tires 1/10 D-70');
        $product->setPrice(mt_rand(20.00, 850.00));
        $product->setUnit('pack.');
        $product->setVatRate(23);
        $product->setDescription('An example of product description. An example of product description. An example of product description.');
        $product->setHasPhoto(false);
        $product->setCurrency('EUR');
        $manager->persist($product);

        $product = new Product();
        $product->setCode(mt_rand(1000, 9999));
        $product->setName('Long Double Wrench Chrome - Vanadium Steel 24cm');
        $product->setPrice(mt_rand(20.00, 850.00));
        $product->setUnit('pcs.');
        $product->setVatRate(23);
        $product->setDescription('An example of product description. An example of product description. An example of product description.');
        $product->setHasPhoto(false);
        $product->setCurrency('EUR');
        $manager->persist($product);
        
        $product = new Product();
        $product->setCode(mt_rand(1000, 9999));
        $product->setName('Micro USB Cable 0.3m/1m/2m/3m Fast Charge');
        $product->setPrice(mt_rand(20.00, 850.00));
        $product->setUnit('pcs.');
        $product->setVatRate(23);
        $product->setDescription('An example of product description. An example of product description. An example of product description.');
        $product->setHasPhoto(false);
        $product->setCurrency('EUR');
        $manager->persist($product);

        $product = new Product();
        $product->setCode(mt_rand(1000, 9999));
        $product->setName('Storage Box organizer, black colour, 20x30x35 cm');
        $product->setPrice(mt_rand(20.00, 850.00));
        $product->setUnit('pcs.');
        $product->setVatRate(23);
        $product->setDescription('An example of product description. An example of product description. An example of product description.');
        $product->setHasPhoto(false);
        $product->setCurrency('EUR');
        $manager->persist($product);

        $product = new Product();
        $product->setCode(mt_rand(1000, 9999));
        $product->setName('CR2032 3V 225mAh Lithium Battery');
        $product->setPrice(mt_rand(20.00, 850.00));
        $product->setUnit('pack.');
        $product->setVatRate(23);
        $product->setDescription('An example of product description. An example of product description. An example of product description.');
        $product->setHasPhoto(false);
        $product->setCurrency('EUR');
        $manager->persist($product);

        $vat = new VatRate();
        $vat->setVatRate(23);
        $manager->persist($vat);
        $vat = new VatRate();
        $vat->setVatRate(8);
        $manager->persist($vat);
        $vat = new VatRate();
        $vat->setVatRate(5);
        $manager->persist($vat);
        $vat = new VatRate();
        $vat->setVatRate(0);
        $manager->persist($vat);

        $unit = new Unit();
        $unit->setShortName('pcs.');
        $unit->setFullName('pieces');
        $manager->persist($unit);
        $unit = new Unit();
        $unit->setShortName('pal.');
        $unit->setFullName('palette');
        $manager->persist($unit);
        $unit = new Unit();
        $unit->setShortName('pack.');
        $unit->setFullName('package');
        $manager->persist($unit);

        $manager->flush();
    }
}