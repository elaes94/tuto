<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'app_profil')]
    public function index(UserRepository $userRepository, ActivityRepository $activityRepository): Response
    {
        // dd($this->getUser());
        $user = $this->getUser();
        // return new Response('Well hi there '.$user->getId());
        return $this->render('profil/index.html.twig', [
            // 'user' => $userRepository->findAll(),
            'user' => $user,
            'activities' => $activityRepository->findBy(['contact' => $user->getId()]),
            // 'activities' => $activityRepository->findByUser($user),
        ]);
    }

    #[Route('/activity/new', name: 'app_profil_activity_new')]
    public function newActivity(Request $request, ActivityRepository $activityRepository): Response
    {
        $activity = new Activity();
        $activity->setContact($this->getUser());
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activityRepository->add($activity, true);

            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/activity/new.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
        // return $this->render('profil/activity/new.html.twig');
    }
}
