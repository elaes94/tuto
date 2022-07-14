<?php

namespace App\Controller;

use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ActivityRepository $activityRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'activities' => $activityRepository->findAll(),
        ]);
    }

    #[Route('search', name: 'app_search', methods: ['GET'])]
    public function search(Request $request, ActivityRepository $activityRepository): Response
    {
        $searchString = $request->query->get('search', "");
        var_dump($searchString);
        return $this->render('main/index.html.twig', [
            'activities' => $activityRepository->search($searchString),
            'searchString' => $searchString,
        ]);
    }

    /**
     * @Route("/test")
     */
    public function test(): Response
    {
        return $this->render('test.html.twig');
    }
}
