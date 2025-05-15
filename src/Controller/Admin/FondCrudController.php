<?php

namespace App\Controller\Admin;

use App\Entity\Fond;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FondCrudController extends AbstractCrudController
{
    use Trait\AddOnlyTrait;

    public static function getEntityFqcn(): string
    {
        return Fond::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            // IdField::new('id'),
            TextField::new('couleurFond'),
            TextField::new('urlFond')
        ];
    }
    
}
