<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommandeCrudController extends AbstractCrudController
{
    use Trait\ShowOnlyTrait;
    
    #[Route('/admin/commande', name: 'admin_commande')]
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('numCommande'),
            DateField::new('dateCommande')->setFormat('dd.MM.yyyy'),
            AssociationField::new('user')->setLabel('Identifiant'),
            TextField::new('prenom')->hideOnIndex(),
            TextField::new('nom')->hideOnIndex(),
            TextField::new('adresse')->hideOnIndex(),
            TextField::new('cp')->hideOnIndex(),
            TextField::new('ville')->hideOnIndex(),
            AssociationField::new('facture')

        ];
    }
    
}
