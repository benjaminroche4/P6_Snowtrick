<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\ChangeAvatarType;
use App\Form\RegistrationFormType;
use App\Form\UpdatePasswordType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/update-password", name="update_password")
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $dto = $this->getUser();
        $form = $this->createForm(UpdatePasswordType::class, $dto);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){
            $dto->setPassword($passwordEncoder->encodePassword($dto, $dto->getPassword()));
            $entityManager->persist($dto);
            $entityManager->flush();
            $this->addFlash(
                'Notification',
                'Votre mot de passe a bien été changé.'
            );
            return $this->redirectToRoute('update_password');
        }

        return $this->render('user/update_password.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/update-avatar", name="update_avatar")
     * @return Response
     */
    public function avatar(Request $request, EntityManagerInterface $entityManager):Response{

        $dto = $this->getUser();
        $form = $this->createForm(ChangeAvatarType::class, $dto);
        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){
            $dto->setFile($dto, $dto->getFile());
            $entityManager->persist($dto);
            $entityManager->flush();
            $this->addFlash(
                'Notification',
                'Votre avatar a bien été changé.'
            );
            return $this->redirectToRoute('update_avatar');
        }

        return $this->render('user/update_avatar.html.twig', [
            'form'=> $form->createView()
        ]);
    }

}
