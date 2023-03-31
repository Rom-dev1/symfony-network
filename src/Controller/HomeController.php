<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    
    #[IsGranted('ROLE_USER')]
    #[Route('/home/{id}', name:'app_comment')]
    public function showComment(PublicationRepository $pub,Request $request, EntityManagerInterface $em,$id): Response
    {   
        $publi = $pub->find($id);
        $comment = new Comment();
        $comment->getPublication();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form['content']->getData();
            $comment->setCreatedAt(new DateTimeImmutable());
            $comment->setContent($data);
            $comment->setPublication($publi);
            $comment->setUser($this->getUser());
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/comment.html.twig', [
            'publications' => $publi,
            'form' => $form,
            'id' => $id,
        ]);
    }
}
