<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user', methods: ['GET', 'POST'])]
    public function index(Security $security, Request $request, UserRepository $userRepository, ClientRepository $clientRepository): Response
    {
        $user = $this->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirectToRoute('app_admin');
        }
        
        if ($user == null) {
            return $this->redirectToRoute('app_login');
        }
        
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $client = $user->getClient();

            $userFirstName = $user->getFirstName();
            if ($userFirstName !== null) {
                $client->setFirstName($userFirstName);
            }

            $userLastName = $user->getLastName();
            if ($userLastName !== null) {
                $client->setLastName($userLastName);
            }

            $clientFullName = $client->getFirstName() . " " . $client->getLastName();
            $client->setFullname($clientFullName);

            $userPhone = $user->getPhone();
            if ($userPhone !== null) {
                $client->setPhone($userPhone);
            }
            $user->setFullname($user->getFirstName() . " " . $user->getLastName());
            $userRepository->save($user, true);
            $clientRepository->save($client,true);
            

            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'form' => $form
        ]);
    }
}
