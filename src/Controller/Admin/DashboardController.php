<?php

namespace App\Controller\Admin;

use App\Entity\Fond;
use App\Entity\Logo;
use App\Entity\Typo;
use App\Entity\User;
use App\Entity\Facture;
use App\Entity\Commande;
use App\Entity\Exemplaire;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\CommandeCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {

        if ($this->getUser() && $this->getUser()->getRoles() == ['ROLE_ADMIN']) {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
            return $this->redirectToRoute('admin_commande_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(CommandeCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
        }
        else {
            return $this->redirectToRoute('app_home');
        }
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CadreDePlaque');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Application', 'fa fa-home', 'app_home');
        yield MenuItem::linkToCrud('Commandes', 'fas fa-list', Commande::class);
        yield MenuItem::linkToCrud('Factures', 'fas fa-file-invoice', Facture::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Exemplaires', 'fas fa-image', Exemplaire::class);
        yield MenuItem::linkToCrud('Fonds', 'fas fa-clone', Fond::class);
        yield MenuItem::linkToCrud('Logos', 'fas fa-icons', Logo::class);
        yield MenuItem::linkToCrud('Typos', 'fas fa-font',Typo::class);
    }
}
