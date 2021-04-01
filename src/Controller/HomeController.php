<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/trick-{title}", name="trick_show")
     * @param Trick $trick
     * @return Response
     */
    public function show(Trick $trick, CommentRepository $commentRepository, $title, Request $request, EntityManagerInterface $entityManager):Response{

        $dto = new Comment();
        $form = $this->createForm(CommentType::class, $dto);

        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $dto->setTrick($trick);
            $dto->setCreatedAt(new \DateTime());
            $dto->setMemberCreator($this->getUser());
            $entityManager->persist($dto); //persist = insert
            $entityManager->flush(); //applique en base de données
            $this->addFlash(
                'Notification',
                'Votre message à bien été publié !'
            );
            return $this->redirectToRoute('trick_show', [ 'title' => $title]);
        }

        $qb = $commentRepository->createQueryBuilder('c')
            ->join('c.trick', 't')->where('t.title = :TITLE')
            ->orderBy('c.createdAt', 'DESC')
            ->setParameter('TITLE', $title);


        $comments = $qb->getQuery()->getResult();

        return $this->render('trick.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }

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

}
