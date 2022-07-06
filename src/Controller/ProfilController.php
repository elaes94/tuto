<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(UserRepository $userRepository, ActivityRepository $activityRepository): Response
    {
        return $this->render('profil/index.html.twig', [
            // 'user' => $userRepository->findAll(),
            'user' => $this->getUser(),
            'activities' => $activityRepository->findAll(),
        ]);
    }
}
