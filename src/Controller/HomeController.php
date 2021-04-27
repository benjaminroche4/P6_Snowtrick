<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    //Constante = valeur qui ne change pas.
    const TAILLE_PAGE = 10;

    /**
     * @Route("/trick/{id}/delete", name="trick_delete_page")
     * @param Trick $trick
     * @return RedirectResponse
     */
    public function delete(Trick $trick) :RedirectResponse{
        $em = $this->getDoctrine()->getManager();
        $em->remove($trick);
        $em->flush();
        $this->addFlash('Notification', 'La figure a bien été supprimé !');

        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/trick-{title}/{page}", name="trick_show")
     * @param Trick $trick
     * @return Response
     */
    public function show(Trick $trick, CommentRepository $commentRepository, $title, Request $request, EntityManagerInterface $entityManager, $page = 0):Response{

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
            //Jointure DoctrineQL 'c'=jointure de la table
            ->join('c.trick', 't')->where('t.title = :TITLE')
            ->orderBy('c.createdAt', 'DESC')
            ->setParameter('TITLE', $title);

        $nbTotal = count( $qb->getQuery()->getResult() );
        $nbPages = intval( $nbTotal/self::TAILLE_PAGE );

        $qb
            ->setMaxResults(self::TAILLE_PAGE)
            ->setFirstResult($page*self::TAILLE_PAGE);

        $comments = $qb->getQuery()->getResult();

        return $this->render('trick.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'form' => $form->createView(),
            'nbPages'=> $nbPages,
            'pageActive'=>$page
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
