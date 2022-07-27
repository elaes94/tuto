<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
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
        return $this->render('main/index.html.twig', [
            'activities' => $activityRepository->search($searchString),
            'searchString' => $searchString,
        ]);
    }

    #[Route('/show/{id}', name: 'app_main_activity_show', methods: ['GET'])]
    public function show(Activity $activity): Response
    {
        return $this->render('main/activity/show.html.twig', [
            'activity' => $activity,
        ]);
    }

    /**
     * @Route("/test")
     */
    public function test(Request $request, ActivityRepository $activityRepository): Response
    {
        $activity = new Activity();

        // $activity->setContact($this->getUser());
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activityRepository->add($activity, true);

            return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('test.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
        return $this->render('test.html.twig');
    }
}
