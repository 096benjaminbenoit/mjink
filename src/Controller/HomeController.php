<?php

namespace App\Controller;

use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Appointment;
use App\Form\AppointmentFormType;
use App\Repository\ClientServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, AppointmentRepository $appointmentRepository, Security $security): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_login');
        }
   
        $user = $this->getUser();
        
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirectToRoute('app_admin');
        }

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

    #[Route('/new', name: 'app_appointment')]
    public function new(Security $security, Request $request, EntityManagerInterface $entityManager, ClientServiceRepository $clientServiceRepository): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_login');
        }
        
        $user = $this->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirectToRoute('app_admin');
        }

        $client = $user->getClient();
        $appointment = new Appointment();
        $appointment->setClient($client);
        
        $form = $this->createForm(AppointmentFormType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $service = $appointment->getService();
            $defaultServiceDuration = $service->getDuration();
            $customServiceDuration = $clientServiceRepository->getServiceDurationFromClient($client, $service);

            $serviceDuration = ($customServiceDuration !== null) ? $customServiceDuration : $defaultServiceDuration;
            $startAt = $appointment->getStart();

            $endAt = clone $startAt;
            $endAt->add(new \DateInterval('PT' . $serviceDuration . 'M'));
            $appointment->setEnd($endAt);

            $entityManager->persist($appointment);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('home/new.html.twig', [
            'controller_name' => 'AppointmentController',
            'form' => $form
        ]);
    }

    #[Route('/{id}/edit', name: 'app_appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Security $security, Request $request, Appointment $appointment, EntityManagerInterface $entityManager, AppointmentRepository $appointmentRepository, ClientServiceRepository $clientServiceRepository): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirectToRoute('app_admin');
        }
        
        $client = $user->getClient();

        $allAppointments = $appointmentRepository->findBy(
            ['client' => $client],
            ['id' => 'ASC']
        );
        
        $form = $this->createForm(AppointmentFormType::class, $appointment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $service = $appointment->getService();
            $defaultServiceDuration = $service->getDuration();
            $customServiceDuration = $clientServiceRepository->getServiceDurationFromClient($client, $service);

            $serviceDuration = ($customServiceDuration !== null) ? $customServiceDuration : $defaultServiceDuration;
            $startAt = $appointment->getStart();

            $endAt = clone $startAt;
            $endAt->add(new \DateInterval('PT' . $serviceDuration . 'M'));
            $appointment->setEnd($endAt);

            $appointmentRepository->save($appointment, true);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home/edit.html.twig', [
            'form' => $form,
            'allAppointments' => $allAppointments
        ]);
    }
    #[Route('/{id}/remove', name: 'app_appointment_remove', methods: ['GET', 'POST'])]
    public function remove(Security $security, Request $request, Appointment $appointment, EntityManagerInterface $entityManager, AppointmentRepository $appointmentRepository): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_login');
        }

        $appointmentRepository->remove($appointment, true);

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
