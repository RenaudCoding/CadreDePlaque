<?php

namespace App\Controller\Admin;

use App\Entity\Logo;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LogoCrudController extends AbstractCrudController
{
    use Trait\AddOnlyTrait;

    public static function getEntityFqcn(): string
    {
        return Logo::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('nomLogo'),
            BooleanField::new('couleurLogo')->renderAsSwitch(false)->setLabel('Logo couleur'),
            TextField::new('urlLogo')


        ];
    }

}
