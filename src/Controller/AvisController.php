<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Evenement;
use App\Form\AvisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AvisController extends AbstractController
{
    #[Route('/avis/creer/{id}', name: 'avis_creer')]
    public function creer(Evenement $evenement, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $avis = new Avis();
        $avis->setEvenement($evenement);
        $avis->setUtilisateur($this->getUser());

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($avis);
            $em->flush();

            $this->addFlash('success', 'Avis publié !');
            return $this->redirectToRoute('evenement_show', ['id' => $evenement->getId()]);
        }

        return $this->render('avis/form.html.twig', [
            'form' => $form->createView(),
            'evenement' => $evenement, // ← indispensable pour Twig
        ]);
    }

    #[Route('/avis/moderer/{id}', name: 'avis_moderer')]
    public function moderer(Avis $avis, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('MODERATE', $avis);

        $avis->setAccepte(true);
        $em->flush();

        $this->addFlash('success', 'Avis modéré avec succès !');
        return $this->redirectToRoute('evenement_show', ['id' => $avis->getEvenement()->getId()]);
    }
}
