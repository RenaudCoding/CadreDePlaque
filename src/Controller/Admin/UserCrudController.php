<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{

    use Trait\ShowOnlyTrait;

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('email'),
            BooleanField::new('password')->renderAsSwitch(false),
            ArrayField::new('roles'),
            BooleanField::new('isVerified')->renderAsSwitch(false),
            AssociationField::new('exemplaires')->setLabel('Nb Exemplaire'),
            ArrayField::new('exemplaires')->hideOnIndex()->setLabel('Liste Exemplaire')
        ];
    }
    
}
