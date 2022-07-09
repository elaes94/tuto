<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Prestation;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ActivityType;
use App\Form\PrestationType;
use App\Form\ProductType;
use App\Repository\ActivityRepository;
use App\Repository\PrestationRepository;
use App\Repository\ProductRepository;
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
        /** @var User $user */
        $user = $this->getUser();
        
        return $this->render('profil/index.html.twig', [
            'user' => $user,
            'activities' => $activityRepository->findBy(['contact' => $user->getId()]),
        ]);
    }

    /*****          Activity       ****/ 
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
    }

    #[Route('/{id}', name: 'app_profil_activity_show', methods: ['GET'])]
    public function showActivity(Activity $activity): Response
    {
        return $this->render('profil/activity/show.html.twig', [
            'activity' => $activity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_profil_activity_edit', methods: ['GET', 'POST'])]
    public function editActivity(Request $request, Activity $activity, ActivityRepository $activityRepository): Response
    {
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activityRepository->add($activity, true);

            return $this->redirectToRoute('app_profil_activity_show', ['id' => $activity->getId(),], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/activity/edit.html.twig', [
            'activity' => $activity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profil_activity_delete', methods: ['POST'])]
    public function deleteActivity(Request $request, Activity $activity, ActivityRepository $activityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activity->getId(), $request->request->get('_token'))) {
            $activityRepository->remove($activity, true);
        }

        return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
    }

    /*****          Prestation       ****/         
    #[Route('/prestation/{id}/new', name: 'app_profil_prestation_new', methods: ['GET', 'POST'])]
    public function newPrestation(Request $request, Activity $activity, PrestationRepository $prestationRepository): Response
    {
        $prestation = new Prestation();
        $prestation->setActivity($activity);
        $form = $this->createForm(PrestationType::class, $prestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prestationRepository->add($prestation, true);

            return $this->redirectToRoute('app_profil_activity_show', ['id' => $activity->getId(),], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/prestation/new.html.twig', [
            'prestation' => $prestation,
            'form' => $form,
        ]);
    }

    /*****          Product       ****/ 
    #[Route('/product/new', name: 'app_profil_product_new', methods: ['GET', 'POST'])]
    public function newProduct(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }
}
