<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Loan;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('Bibliothèque - Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Catalogue');
        yield MenuItem::linkToCrud('Livres', 'fa fa-book', Book::class);
        yield MenuItem::linkToCrud('Catégories', 'fa fa-tags', Category::class);

        yield MenuItem::section('Emprunts');
        yield MenuItem::linkToCrud('Emprunts', 'fa fa-exchange', Loan::class);

        yield MenuItem::section('Site');
        yield MenuItem::linkToRoute('Voir le site', 'fa fa-globe', 'app_home');
    }
}
