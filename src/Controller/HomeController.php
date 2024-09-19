<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprises = $entrepriseRepository->findBy([], ["raisonSociale" => "DESC"]);
        return $this->render('home/index.html.twig', [
            'entreprises' => $entreprises

        ]);
    
    }

    #[Route('/home/new', name: 'new_home')]
    #[Route('/home/{id}/edit', name: 'edit_home')]
    public function new_edit(Entreprise $entreprise = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$entreprise){
            $entreprise = new Entreprise();
        }

        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entreprise = $form->getData();
            // prepare en PDO
            $entityManager->persist($entreprise);
            // Execute en PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/new.html.twig',[
            'formAddEntreprise' => $form
        
        ]);
    }


    #[Route('/home/{id}/supp', name: 'supp_entreprise')]
    public function supp(Entreprise $entreprise, EntityManagerInterface $entityManager ){

        $entityManager->remove($entreprise);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');

    }


    #[Route('/home/{id}', name: 'show_home')]
    public function show(Entreprise $entreprise): Response
    {
        return $this->render('home/show.html.twig', [
            'entreprise' => $entreprise

        ]);

    }
}

