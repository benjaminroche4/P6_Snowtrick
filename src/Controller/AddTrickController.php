<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddTrickController extends AbstractController
{
    /**
     * @Route("/add/trick", name="add_trick")
     */
    public function index(): Response
    {
        return $this->render('add_trick/index.html.twig', [
            'controller_name' => 'AddTrickController',
        ]);
    }
}
