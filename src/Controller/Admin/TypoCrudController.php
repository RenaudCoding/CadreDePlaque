<?php

namespace App\Controller\Admin;

use App\Entity\Typo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TypoCrudController extends AbstractCrudController
{
    use Trait\AddOnlyTrait;

    public static function getEntityFqcn(): string
    {
        return Typo::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('nomTypo'),
            TextField::new('urlTypo')
        ];
    }
    
}
