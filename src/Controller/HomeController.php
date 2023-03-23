<?php

namespace App\Controller;

use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PublicationRepository $pub): Response
    {   
        $publi = $pub->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'publications' => $publi
        ]);
    }
}
