<?php

namespace App\Controller\Admin;

use App\Entity\Appointment;
use App\Form\AppointmentFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AppointmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Appointment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Rendez-vous')
            ->setEntityLabelInPlural('Rendez-vous')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Créer un rendez-vous');
            });
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('client'),
            DateTimeField::new('start')->setLabel('Début')->setFormat('dd/MM/YYYY - HH:mm'),
            AssociationField::new('employee')->setLabel('Employé'),
            AssociationField::new('service')->setLabel('Prestation'),
        ];
    }
}
