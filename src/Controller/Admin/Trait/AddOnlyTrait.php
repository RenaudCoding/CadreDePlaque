<?php

namespace App\Controller\Admin\Trait;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

trait AddOnlyTrait
{
    public function configureActions(Actions $actions): Actions
    {
        $actions->disable(Action::EDIT, Action::DELETE);
    
        return $actions;
    }
    
}

