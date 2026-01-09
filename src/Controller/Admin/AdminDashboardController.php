<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Evenement;
use App\Entity\Avis;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminDashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        // Redirection vers le CRUD le plus important pour l'admin
        return $this->redirect($this->generateUrl('admin_dashboard_evenement_index'));
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Relais & Châteaux Admin');
    }

    public function configureMenuItems(): iterable
    {
        $user = $this->getUser();

        // Menu réservé aux admins uniquement
        if ($user && in_array('ROLE_ADMIN', $user->getRoles())) {
            yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class);
        }

        // Tous les utilisateurs connectés peuvent voir les événements
        if ($user) {
            yield MenuItem::linkToCrud('Événements', 'fa fa-calendar', Evenement::class);
        }

        // Les avis
        if ($user) {
            yield MenuItem::linkToCrud('Avis', 'fa fa-comments', Avis::class);
        }

        // Optionnel : ajouter un lien vers le frontend ou logout
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-home', 'homepage');
    }
}
