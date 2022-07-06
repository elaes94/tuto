<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType_old;
use App\Repository\ActivityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activity_old')]
class ActivityController_old extends AbstractController
{
    #[Route('/', name: 'app_activity_old_index', methods: ['GET'])]
    public function index(Request $request, ActivityRepository $activityRepository): Response
    {
        $page = $request->query->get('page', 1);
        return $this->render('activity_old/index.html.twig', [
            'activities' => $activityRepository->getPage($page)[0],
            'totalPage' => $activityRepository->getPage($page)[1],
            'currentPage' => $page,
        ]);
    }

    #[Route('/search', name: 'app_activity_old_search', methods: ['GET'])]
    public function search(Request $request, ActivityRepository $activityRepository): Response
    {
        $searchString = $request->query->get('search', "");
        var_dump($searchString);
        $page = $request->query->get('page', 1);
        return $this->render('activity_old/index.html.twig', [
            'activities' => $activityRepository->getPage($page)[0],
            'totalPage' => $activityRepository->getPage($page)[1],
            'currentPage' => $page,
            'searchString' => $searchString,
        ]);
    }

    #[Route('/new', name: 'app_activity_old_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ActivityRepository $activityRepository): Response
    {
        $activity = new Activity();
        $form = $this->createForm(ActivityType_old::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activityRepository->add($activity, true);

            return $this->redirectToRoute('app_activity_old_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activity_old/new.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activity_old_show', methods: ['GET'])]
    public function show(Activity $activity): Response
    {
        return $this->render('activity_old/show.html.twig', [
            'activity' => $activity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activity_old_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activity $activity, ActivityRepository $activityRepository): Response
    {
        $form = $this->createForm(ActivityType_old::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activityRepository->add($activity, true);

            return $this->redirectToRoute('app_activity_old_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activity_old/edit.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activity_old_delete', methods: ['POST'])]
    public function delete(Request $request, Activity $activity, ActivityRepository $activityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activity->getId(), $request->request->get('_token'))) {
            $activityRepository->remove($activity, true);
        }

        return $this->redirectToRoute('app_activity_old_index', [], Response::HTTP_SEE_OTHER);
    }
}
