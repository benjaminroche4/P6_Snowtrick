<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function show(Trick $trick):Response{
        return $this->render('trick.html.twig', [
            'trick' => $trick
        ]);
    }



}
