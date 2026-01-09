<?php

namespace App\Controller;

use App\Entity\Evenement;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EvenementController extends AbstractController
{
    #[Route('/evenements', name: 'evenement_list')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $evenements = $doctrine->getRepository(Evenement::class)->findAll();

        return $this->render('evenement/list.html.twig', [
            'evenements' => $evenements
        ]);
    }

    #[Route('/evenement/{id}', name: 'evenement_show')]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement
        ]);
    }
}
