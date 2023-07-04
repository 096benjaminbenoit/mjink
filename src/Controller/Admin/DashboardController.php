<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\Service;
use App\Entity\Employee;
use App\Entity\Appointment;
use App\Entity\Availability;
use App\Entity\ClientService;
use App\Repository\AppointmentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Bundle\SecurityBundle\Security;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'app_admin')]
    public function dashboard(Security $security, Request $request, AppointmentRepository $appointmentRepository): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_login');
        }

        $appointments = $appointmentRepository->findAll();
            $planning = [];
            foreach($appointments as $appointment) {
                $employee = $appointment->getEmployee()->getFirstName();
                if($employee == 'Amélie') {
                    $backgroundColor = '#579BB1';
                } else if ($employee == 'Jonathan') {
                    $backgroundColor = '#395144';
                } else if ($employee == 'Sarah') {
                    $backgroundColor = '#E26868';
                }
                $planning[] = [
                    'id' => $appointment->getId(),
                    'title' => $appointment->getClient()->getFullname(),
                    'start' => $appointment->getStart()->format('Y-m-d H:i:s'),
                    // 'end' => $appointment->getEnd()->format('Y-m-d H:i:s'),
                    'description' => $appointment->getService()->getName(),
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $backgroundColor
                ];
            }

        $data = json_encode($planning);
        return $this->render('admin/dashboard.html.twig', [
            'data' => $data
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MJ INK - Barber & Tattoo');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');
        yield MenuItem::linkToCrud('Prestations', 'fas fa-scissors', Service::class);
        yield MenuItem::linkToCrud('Disponibilités', 'fas fa-calendar', Availability::class);
        yield MenuItem::linkToCrud('Employés', 'fas fa-user-group', Employee::class);
        yield MenuItem::linkToCrud('Clients', 'fas fa-user', Client::class);
        yield MenuItem::linkToCrud('Rendez-vous', 'fas fa-calendar-check', Appointment::class);
        yield MenuItem::linkToCrud('Personnalisation des prestations', 'fas fa-gear', ClientService::class);
    }
}
