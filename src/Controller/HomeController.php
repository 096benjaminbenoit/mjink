<?php

namespace App\Controller;

use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, AppointmentRepository $appointmentRepository): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();
        $client = $user->getClient();
        $upcomingAppointment = $appointmentRepository->findUpcomingAppointmentsForClient();

        $allAppointments = $appointmentRepository->findBy(
            ['client' => $client],
            ['id' => 'ASC']
        );

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user,
            'upcomingAppointment' => $upcomingAppointment,
            'allAppointments' => $allAppointments
        ]);
    }
}
