<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\User;
use App\Form\PublicationType;
use App\Form\UserType;
use App\Repository\PublicationRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{   
    private $repository;
    private $em;

    public function __construct(PublicationRepository $repository,EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    #[Route('/user', name: 'app_user')]
    public function index(Request $request): Response
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
            $this->em->persist($post);
            $this->em->flush();

            return $this->redirectToRoute('app_user');
        }
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/edit', name:'app_edit')] 
    public function edit(Request $request,SluggerInterface $slugger): Response
    {   
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid()){
            $avatarFile = $form->get('avatar')->getData();
                if($avatarFile){
                    $avatarFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeAvatarname = $slugger->slug($avatarFilename);
                    $newFilename = $safeAvatarname.'-'.uniqid().'.'.$avatarFile->guessExtension();
                    $avatarFile->move($this->getParameter('avatar_directory'),$newFilename);
                    $new = $user->setAvatar($newFilename);
                    dump($new);
                }
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('app_user');
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
