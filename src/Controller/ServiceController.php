<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $allServices = $serviceRepository->findAll();
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
            'allServices' => $allServices
        ]);
    }
}
