<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\OrderBy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(PublicationRepository $repository,Request $request,EntityManagerInterface $manager): Response
    {   
        $user = $this->getUser();
        $post = new Publication();
        $form = $this->createForm(PublicationType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form['content']->getData();
            $post->setContent($data);
            $post->setCreatedAt(new DateTimeImmutable());
            $post->setUser($this->getUser()) ;
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('app_user');
        }
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
