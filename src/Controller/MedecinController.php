<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Form\MedecinType;
use App\Repository\MedecinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MedecinSearchType;

class MedecinController extends AbstractController
{
    #[Route('/medecin', name: 'app_medecin')]
    public function index(): Response
    {
        return $this->render('medecin/index.html.twig', [
            'controller_name' => 'MedecinController',
        ]);
    }

    #[Route('/medecin/list', name: 'app_medecin_list')]
    public function medecinList(MedecinRepository $medecinRepository, Request $request): Response
    {
        $searchForm = $this->createForm(MedecinSearchType::class);
        $searchForm->handleRequest($request);

        $medecins = [];

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            // Get the date from the form
            $dateOfBirth = $searchForm->get('dateOfBirth')->getData();
            $medecins = $medecinRepository->findByDateOfBirth($dateOfBirth);
        } else {
            $medecins = $medecinRepository->findAll();
        }

        return $this->render('medecin/list.html.twig', [
            'medecins' => $medecins,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    #[Route('/medecin/delete/{id}', name: 'app_medecin_delete')]
    public function deleteMedecin(int $id, MedecinRepository $medecinRepository, EntityManagerInterface $em)
    {
        $medecin = $medecinRepository->find($id);
        if (!$medecin) {
            throw $this->createNotFoundException('Medecin not found');
        }

        $em->remove($medecin);
        $em->flush();
        return $this->redirectToRoute('app_medecin_list');
    }

    #[Route('/medecin/update/{id}', name: 'app_medecin_update')]
    public function updateMedecin(int $id, MedecinRepository $medecinRepository, Request $request, EntityManagerInterface $em)
    {
        $medecin = $medecinRepository->find($id);
        if (!$medecin) {
            throw $this->createNotFoundException('Medecin not found');
        }

        $form = $this->createForm(MedecinType::class, $medecin);
        $form->add('Update', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_medecin_list');
        }

        return $this->render('medecin/form.html.twig', [
            'title' => 'Update Medecin',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/medecin/new', name: 'app_medecin_new')]
    public function newMedecin(Request $request, EntityManagerInterface $em)
    {
        $medecin = new Medecin();
        $form = $this->createForm(MedecinType::class, $medecin);
        $form->add('Add', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($medecin);
            $em->flush();
            return $this->redirectToRoute('app_medecin_list');
        }

        return $this->render('medecin/form.html.twig', [
            'title' => 'Add Medecin',
            'form' => $form->createView(),
        ]);
    }
}
