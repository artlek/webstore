<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Unit;
use App\Entity\VatRate;
use App\Form\AddUnitFormType;
use App\Form\AddVatFormType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class UnitVatController extends AbstractController
{
    #[Route('/vendor/unit', name: 'app_unit')]
    public function showUnit(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager): Response
    {
        $unit = new Unit;
        $form = $this->createForm(AddUnitFormType::class, $unit);
        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid())
        {
            $entityManager->persist($unit);
            $entityManager->flush();

            $this->addFlash(
                'positiveInfo',
                'Unit of measure (' . $unit->getFullName() . ') has been added.'
            );
        }

        $repository = $doctrine->getRepository(Unit::class);
        $units = $repository->findAll();

        return $this->render('unit.html.twig', [
            'form' => $form->createView(),
            'units' => $units,
        ]);
    }

    #[Route('/vendor/vat', name: 'app_vat')]
    public function showVat(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $entityManager): Response
    {
        $vat = new VatRate;
        $form = $this->createForm(AddVatFormType::class, $vat);
        $form->handleRequest($request);

        if($form->isSubmitted() AND $form->isValid())
        {
            $entityManager->persist($vat);
            $entityManager->flush();

            $this->addFlash(
                'positiveInfo',
                'Vat rate (' . $vat->getVatRate() . ') has been added.'
            );
        }

        $repository = $doctrine->getRepository(VatRate::class);
        $vats = $repository->findAll();

        return $this->render('vat.html.twig', [
            'form' => $form->createView(),
            'vats' => $vats,
        ]);
    }

    #[Route('/vendor/unit/delete', name: 'app_delete_unit')]
    public function deleteUnit(EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        if(isset($_POST['unit-id']))
        {
            $entityManager = $doctrine->getManager();
            $unit = $entityManager->getRepository(Unit::class)->find($_POST['unit-id']);
            if(!empty($unit))
            {
                $entityManager->remove($unit);
                $entityManager->flush();

                $this->addFlash(
                    'positiveInfo',
                    'Unit of measure (' . $unit->getFullName() . ') has been deleted.'
                );
            }
        }
        return $this->redirectToRoute('app_unit');
    }

    #[Route('/vendor/vat/delete', name: 'app_delete_vat')]
    public function deleteVat(EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        if(isset($_POST['vat-id']))
        {
            $entityManager = $doctrine->getManager();
            $vat = $entityManager->getRepository(VatRate::class)->find($_POST['vat-id']);
            if(!empty($vat))
            {
                $entityManager->remove($vat);
                $entityManager->flush();

                $this->addFlash(
                    'positiveInfo',
                    'Vat rate (' . $vat->getVatRate() . ') has been deleted.'
                );
            }
        }
        return $this->redirectToRoute('app_vat');
    }
}
