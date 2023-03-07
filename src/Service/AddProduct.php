<?php

namespace App\Service;

use Gumlet\ImageResize;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\GetCurrency;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;

class AddProduct
{
    private $photoDir = 'uploads/photos/products/';

    public function __construct(private EntityManagerInterface $entityManager, private ManagerRegistry $doctrine, private RequestStack $requestStack, private GetCurrency $getCurrency)
    {

    }

    // Adds new product to product list
    public function add($photo, $product)
    {
        $session = $this->requestStack->getSession();
        if(is_uploaded_file($photo))
        {
            $tempDir = $_FILES['form']['tmp_name']['photo'];
            $size = $_FILES['form']['size']['photo'];
            $newFilename = $product->getCode() . '.jpg';
            $newFilenameThumb = $product->getCode() . 'thumb.jpg';
            if(getimagesize($tempDir)['0'] == getimagesize($tempDir)['1'] AND 
                getimagesize($tempDir)['mime'] == 'image/jpeg'
                AND $size < 1000000)
            {
                $image = new ImageResize($tempDir);
                $image->quality_jpg = 100;
                $image->resizeToBestFit(500, 500);
                $image->save($this->photoDir . $newFilename);
                $imageThumb = new ImageResize($tempDir);
                $image->quality_jpg = 100;
                $imageThumb->resizeToBestFit(180, 180);
                $imageThumb->save($this->photoDir . $newFilenameThumb);
                $product->setHasPhoto(true);
            }
            else
            {
                $session->getFlashBag()->add(
                    'negativeInfo',
                    'Only square jpg or jpeg images allowed, max. 1 MiB.'
                );
                return null;
            }
        }
        else
        {
            $product->setHasPhoto(false);
        }
        $product->setCurrency($this->getCurrency->getCurrency());
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        $productsRepo = $this->doctrine->getRepository(Product::class);
        $products = $productsRepo->findAll();
        $session->getFlashBag()->add(
            'positiveInfo',
            'Product (' . $product->getName() . ') was added.'
        );
    }
}