<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\AddType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddController extends AbstractController
{

    /**
     * @Route("/upload-photo", name="upload-photo")
     */
    public function uploadPhoto(Request $request){

        // Partie upload des photos
        $filename = uniqid().'.jpg';
        move_uploaded_file($_FILES['file']['tmp_name'], 'img/upload/'.$filename);

        // + le nom du fichier en session
        $request->getSession()>get;

        return $this->json('ok');

    }

    /**
     * @Route("/add", name="add")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dto = new Trick();
        $form = $this->createForm(AddType::class, $dto);
        $form->handleRequest($request); //transvase les données de la requête dans le DTO


        if($form->isSubmitted() and $form->isValid()){
            $file = $form['mainPhotoUrl']->getData();
            $fileName = uniqid('photo_').'.jpg';
            $file->move('img/upload', $fileName);

            $dto->setMainPhotoUrl($fileName);
            $dto->setMemberCreator($this->getUser());
            $dto->setCreatedAt(new \DateTime());
            $entityManager->persist($dto);
            $entityManager->flush();

        }

        return $this->render('add/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
