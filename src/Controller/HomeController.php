<?php

namespace App\Controller;

use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    
    #[Route('/member', name:'app_member')]
    #[IsGranted('ROLE_USER')]
    public function showMember(UserRepository $members) : Response
    {
        $members = $members->findAll();

        return $this->render('home/member.html.twig', [
            'members' => $members
        ]);
    }
}
