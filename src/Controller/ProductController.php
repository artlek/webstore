<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Product;
use App\Entity\CartProduct;
use App\Entity\Unit;
use App\Entity\VatRate;
use App\Form\ProductForm;
use App\Form\AddProductToSessionType;
use App\Form\ChangeProductDescriptionFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\CartManager;
use App\Service\AnyUnitExists;
use App\Service\AnyVatExists;
use App\Service\GetUnitChoices;
use App\Service\GetVatChoices;
use App\Service\AddProduct;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use \Gumlet\ImageResize;

class ProductController extends AbstractController
{
    private $requestStack;
    private $dir = 'uploads/photos/products/';

    #[Route('/vendor/product', name: 'app_vendor_product_list')]
    public function showProductVendor(Request $request, ManagerRegistry $doctrine, ProductRepository $productRepository): Response
    {
        $repository = $doctrine->getRepository(Product::class);
        $products = $repository->findAll();
        return $this->render('product_list_vendor.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/vendor/product/add', name: 'app_vendor_add_product')]
    public function addProductVendor(Request $request, ProductRepository $productRepository, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, AnyUnitExists $unitExists, AnyVatExists $vatExists, GetUnitChoices $units, GetVatChoices $vats, AddProduct $addProduct): Response
    {
        if($vatExists->anyVatExists() == true AND $unitExists->anyUnitExists() == true)
        {
            $product = new Product;
            $form = $this->createFormBuilder($product)
                ->add('code', IntegerType::class)
                ->add('name', TextType::class)
                ->add('price', NumberType::class)
                ->add('unit', ChoiceType::class, [
                    'choices' => $units->getUnitChoices()
                ])
                ->add('vatRate', ChoiceType::class, [
                    'choices' => $vats->getVatChoices()
                ])
                ->add('photo', FileType::class, [
                    'mapped' => false,
                    'required' => false
                ])
                ->add('description', TextareaType::class)
                ->add('submit', SubmitType::class)
                ->getForm()
            ;

            $form->handleRequest($request);
            $productsRepo = $doctrine->getRepository(Product::class);

            if ($form->isSubmitted() AND $form->isValid()) {
                $photo = $form['photo']->getData();
                $addProduct->add($photo, $product);
            }

            return $this->render('add_product.html.twig', [
                'form' => $form->createView()
            ]);
        }
        else
        {
            return $this->render('add_product.html.twig', [
                'noUnitsOrVats' => 'There is no units or vat rates set. Add it in Units and Vat rates tab.'
            ]);
        }
    }

    #[Route('/vendor/product/delete', name: 'app_delete_product')]
    public function delete(ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {   
        if(isset($_POST['code'])){
            $post = $_POST['code'];
            $product = $doctrine->getRepository(Product::class)->findOneBy(['code' => $post]);
        }

        if(!empty($product)){
            $entityManager->remove($product);
            $entityManager->flush();
            if($product->isHasPhoto() == true)
            {
                unlink($this->dir . $product->getCode() . '.jpg');
                unlink($this->dir . $product->getCode() . 'thumb.jpg');
            }
            $this->addFlash(
                'positiveInfo',
                'Product (' . $product->getName() . ') was deleted.'
            );
            return $this->redirectToRoute('app_vendor_product_list');
        }
        else{
            return $this->redirectToRoute('app_vendor_product_list');
        }
    }

    #[Route('/product', name: 'app_product_list')]
    public function showProductsClient(ManagerRegistry $doctrine, Request $request) : Response
    {   
        $products = $doctrine->getRepository(Product::class)->findAll();

        return $this->render('product_list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/{code}', name: 'app_client_product')]
    public function showClientProductDetails(ManagerRegistry $doctrine, $code, Request $request): Response
    {
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->findOneBy(['code' => $code]);
        if($product)
        {
            return $this->render('product_client.html.twig', [
                'product' => $product
            ]);
        }
        else
        {
            $this->addFlash(
                'negativeInfo',
                'Product does not exist.'
            );
            return $this->redirectToRoute('app_product_list');
        }
    }

    #[Route('/vendor/product/{code}', name: 'app_vendor_product')]
    public function showVendorProductDetails(ManagerRegistry $doctrine, $code, Request $request): Response
    {
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->findOneBy(['code' => $code]);
        if($product)
        {
            return $this->render('product_vendor.html.twig', [
                'product' => $product
            ]);
        }
        else
        {
            $this->addFlash(
                'negativeInfo',
                'Product does not exist.'
            );
            return $this->redirectToRoute('app_vendor_product_list');
        }
    }

    #[Route('/vendor/change_price', name: 'app_change_price')]
    public function changePrice(ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {   
        if(isset($_POST['code'])){
            $code = $_POST['code'];
            $product = $doctrine->getRepository(Product::class)->findOneBy(['code' => $code]);
        }

        if(!empty($product))
        {
            $newPrice = str_replace(',', '.', $_POST['new-price']);
            if($newPrice < 1000000000 AND $newPrice >= 0)
            {
                $newPrice = round($newPrice, 2);
                $product->setPrice($newPrice);
                $entityManager->persist($product);
                $entityManager->flush();

                $this->addFlash(
                    'positiveInfo',
                    'Price was changed.'
                );
                return $this->redirect('/vendor/product/' . $product->getCode());
            }
            else
            {
                $this->addFlash(
                    'negativeInfo',
                    'Invalid value of new price.'
                );
                return $this->redirect('/vendor/product/' . $product->getCode());
            }
        }
        else
        {
            return $this->redirectToRoute('app_vendor_product_list');
        }
    }

    #[Route('/vendor/change_available', name: 'app_change_available')]
    public function changeAvailable(ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {   
        if(isset($_POST['code'])){
            $code = $_POST['code'];
            $product = $doctrine->getRepository(Product::class)->findOneBy(['code' => $code]);
        }

        if(!empty($product))
        {
            $product->setBlocked($_POST['new-available']);
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'positiveInfo',
                'Product available has been changed.'
            );
            return $this->redirect('/vendor/product/' . $product->getCode());
        }
        else
        {
            return $this->redirectToRoute('app_vendor_product_list');
        }
    }

    #[Route('/vendor/update_photo', name: 'app_update_photo')]
    public function updatePhoto(ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {   
        if(isset($_POST['code'])){
            $code = $_POST['code'];
            $product = $doctrine->getRepository(Product::class)->findOneBy(['code' => $code]);
        }

        if(!empty($product))
        {
            $tempDir = $_FILES['new-photo']['tmp_name'];
            $size = $_FILES['new-photo']['size'];
            $newFilename = $product->getCode() . '.jpg';
            $newFilenameThumb = $product->getCode() . 'thumb.jpg';
            if(getimagesize($tempDir)['0'] == getimagesize($tempDir)['1'] AND 
                getimagesize($tempDir)['mime'] == 'image/jpeg'
                AND $size < 1000000)
            {
                $image = new ImageResize($tempDir);
                $image->quality_jpg = 100;
                $image->resizeToBestFit(500, 500);
                $image->save($this->dir . $newFilename);
                $imageThumb = new ImageResize($tempDir);
                $image->quality_jpg = 100;
                $imageThumb->resizeToBestFit(180, 180);
                $imageThumb->save($this->dir . $newFilenameThumb);
                $product->setHasPhoto(true);
                $entityManager->persist($product);
                $entityManager->flush();

                $this->addFlash(
                    'positiveInfo',
                    'Product photo has been updated.'
                );

                return $this->redirect('/vendor/product/' . $product->getCode());
            }
            else
            {
                $this->addFlash(
                    'negativeInfo',
                    'Only square jpg or jpeg images allowed, max. 1 MiB.'
                );

                return $this->redirect('/vendor/product/' . $product->getCode());
            }
        }
        else
        {
            return $this->redirectToRoute('app_vendor_product_list');
        }
    }

    #[Route('/vendor/change_description', name: 'app_change_description')]
    public function changeDescription(ManagerRegistry $doctrine, EntityManagerInterface $entityManager)
    {   
        if(isset($_POST['code'])){
            $code = $_POST['code'];
            $product = $doctrine->getRepository(Product::class)->findOneBy(['code' => $code]);
        }
        
        if(!empty($product))
        {
            $desc = $_POST['new-description'];
            if(!preg_match('/[^0-9a-zA-ZęółśążźćńĘÓŁŚĄŻŹĆŃ\s,.()-]/m', $desc) AND strlen($desc) >= 3 AND strlen($desc) < 1000)
            {
                $product->setDescription($desc);
                $entityManager->persist($product);
                $entityManager->flush();

                $this->addFlash(
                    'positiveInfo',
                    'Product description has been changed.'
                );
            }
            else
            {
                $this->addFlash(
                    'negativeInfo',
                    'Description contains invalid characters. Min. 3 characters and max. 1000.'
                );
            }
            return $this->redirect('/vendor/product/' . $product->getCode());
        }
        else
        {
            return $this->redirectToRoute('app_vendor_product_list');
        }
    }
}