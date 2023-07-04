<?php

namespace App\Controller\Admin;

use App\Entity\ClientService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ClientServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ClientService::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Prestation personnalisée')
            ->setEntityLabelInPlural('Prestations personnaliées')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Créer une prestation personnalisée');
            });
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('client'),
            AssociationField::new('service')->setLabel('Prestation'),
            IntegerField::new('duration')->setLabel('Durée')
        ];
    }
}
