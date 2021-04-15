<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\UpdatePasswordType;
use Doctrine\ORM\EntityManager;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/update-password", name="update_password")
     * @return Response
     */
    public function index(Request $request, EntityManager $entityManager): Response
    {
        $dto = new Member();
        $form = $this->createForm(UpdatePasswordType::class, $dto);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){
            $dto->setUsername($this->getUser());
            $entityManager->persist($dto);
            $entityManager->flush();
            $this->addFlash(
                'Notification',
                'Votre mot de passe a bien été changé.'
            );
            return $this->redirectToRoute('update_password');
        }

        return $this->render('user/update_password.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
