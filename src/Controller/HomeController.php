<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home(){
        $repo = $this->getDoctrine()->getRepository(Trick::class);
        $tricks = $repo->findAll();

        return $this->render('home.html.twig', [
            'controller_name' => 'HomeController',
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("/trick-{title}", name="trick_show")
     * @param Trick $trick
     * @return Response
     */
    public function show(Trick $trick, TrickRepository $trickRepository, $title, Request $request, EntityManagerInterface $entityManager):Response{

        $dto = new Comment();
        $form = $this->createForm(CommentType::class, $dto);

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $dto->setTrick($trick);
            $dto->setCreatedAt(new \DateTime());
            $dto->setMemberCreator($this->getUser());
            $entityManager->persist($dto); //persist = insert
            $entityManager->flush(); //applique en base de données
            return $this->redirectToRoute('trick_show', [ 'title' => $title]);
        }

        $comments = $trickRepository->findOneByTitle($title)->getComments();

        return $this->render('trick.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }

}
