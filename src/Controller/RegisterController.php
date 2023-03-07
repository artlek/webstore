<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\RegistrationFormType;
use App\Form\RegistrationVendorFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VendorRepository;
use Doctrine\Persistence\ManagerRegistry;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        $vendorsRepo = $doctrine->getRepository(Client::class);
        $vendors = $vendorsRepo->findAll();
        $isVendor = false;
        for($i = 0; $i < count($vendors); $i++)
        {
            if($vendors[$i]->getRoles()['0'] == 'ROLE_VENDOR')
            {
                $isVendor = true;
            }
        }
        if($isVendor == false)
        {
            return $this->redirectToRoute('app_vendor_register');
        }
        $user = new Client();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'positiveInfo',
                'Registration was successful. Now you can login.'
            );
            return $this->redirectToRoute('app_register');
        }

        return $this->render('register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/register-vendor', name: 'app_vendor_register')]
    public function vendorRegister(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        $vendorsRepo = $doctrine->getRepository(Client::class);
        $vendors = $vendorsRepo->findAll();
        $isVendor = false;
        for($i = 0; $i < count($vendors); $i++)
        {
            if($vendors[$i]->getRoles()['0'] == 'ROLE_VENDOR')
            {
                $isVendor = true;
            }
        }
        if($isVendor == true)
        {
            return $this->redirectToRoute('app_register');
        }
        $user = new Client();
        $form = $this->createForm(RegistrationVendorFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(array('ROLE_VENDOR'));
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'positiveInfo',
                'Registration for vendor was successful. Now you can login.'
            );
            return $this->redirectToRoute('app_vendor_register');
        }

        return $this->render('register_vendor.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}