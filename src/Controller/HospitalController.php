<?php

namespace App\Controller;

use App\Entity\Hospital;
use App\Form\HospitalType;
use App\Repository\HospitalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HospitalController extends AbstractController
{
    #[Route('/hopital', name: 'app_hopital')]
    public function index(): Response
    {
        return $this->render('hopital/index.html.twig', [
            'controller_name' => 'HopitalController',
        ]);
    } 

    #[Route('/hopital/list', name: 'app_hopital_list')]
    public function hopitalList(HospitalRepository $hospitalRepository){
        $hospitals= $hospitalRepository->findAll();
        return $this->render('hopital/list.html.twig',[
            'hospitals' => $hospitals
        ]);
    }
    


    #[Route('/hopital/delete/{id}', name: 'app_hopital_delete')]
    public function deleteHopital($id, HospitalRepository $hopitalRepository, EntityManagerInterface $em){
        $hopital = $hopitalRepository->find($id);
        $em->remove($hopital);
        $em->flush();
        return $this->redirectToRoute('app_hopital_list');
        
    }


    #[Route('/hopital/update/{id}', name: 'app_hopital_update')]
public function updateHopital(int $id, HospitalRepository $hopitalRepository, Request $request, EntityManagerInterface $em)
{
    // Fetch the Hospital entity by ID
    $hopital = $hopitalRepository->find($id);

    // Check if the hospital exists
    if (!$hopital) {
        throw $this->createNotFoundException('Hospital not found');
    }

    // Create a form and populate it with the existing hospital data
    $form = $this->createForm(HospitalType::class, $hopital);
    $form->add('update',SubmitType::class);
    $form->handleRequest($request);

    // Process the form if it is submitted and valid
    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush(); // Save the updated hospital entity
        return $this->redirectToRoute('app_hopital_list'); 
    }

    // Render the form view
    return $this->render('hopital/form.html.twig', [
        'title' => 'Update Hospital',
        'form' => $form->createView(),
    ]);
}



    #[Route('/hopital/new', name: 'app_hopital_new')]
    public function newHopital(Request $request,EntityManagerInterface $em){
        $hopital= new Hospital();
        $form= $this->createForm(HospitalType::class, $hopital);
        $form->add('Add',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em->persist($hopital);
            $em->flush();
            return $this->redirectToRoute('app_hopital_list');
        }


        return $this->render('hopital/form.html.twig',[
            'title' => 'Add hopital',
            'form' => $form->createView(),
        ]);
    }
    
}

/*
    */