<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateTrickController extends AbstractController
{
    /**
     * @Route("/update-trick-{id}", name="update_trick")
     * @param Trick $trick
     * @return Response
     */
    public function index(Trick $trick): Response
    {
        return $this->render('update_trick/index.html.twig', [
            'controller_name' => 'UpdateTrickController',
            'trick' => $trick
        ]);
    }
}
