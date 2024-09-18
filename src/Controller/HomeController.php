<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/home/{id}', name: 'show_home')]
    public function show(Entreprise $entreprise): Response
    {
        return $this->render('home/show.html.twig', [
            'entreprise' => $entreprise

        ]);

    }


}
