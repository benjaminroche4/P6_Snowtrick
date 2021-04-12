<?php

namespace App\Controller;

use App\DTO\TrickDTO;
use App\Entity\Photo;
use App\Entity\Trick;
use App\Form\AddType;
use App\Form\TrickDTOType;
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

        // Ajout du nom de fichier en session
        $photos = $request->getSession()->get('photos',[]);
        $photos[]= $filename;
        $request->getSession()->set('photos', $photos);

        return $this->json('ok');

    }

    /**
     * @Route("/add", name="add")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dto = new TrickDTO();
        $form = $this->createForm(TrickDTOType::class, $dto);
        $form->handleRequest($request); //transvase les données de la requête dans le DTO


        if($form->isSubmitted() and $form->isValid()){
            // Persiste le trick
            $file = $form['mainPhotoUrl']->getData();
            $fileName = uniqid('photo_').'.jpg';
            $file->move('img/upload', $fileName);

            $dto->setMainPhotoUrl($fileName);
            $dto->setMemberCreator($this->getUser());
            $dto->setCreatedAt(new \DateTime());
            $entityManager->persist($dto);

            // Persiste les photos upload d'on le nom est en session
            $photos = $request->getSession()->get('photos', []);
            foreach($photos as $photo){
                $p = new Photo();
                //fait la relation entre les deux tables
                $p->setTrick($dto);
                $p->setUrl($photo);

                $entityManager->persist($p);
            }

            $entityManager->flush();
            $this->addFlash('Notification', 'Votre figure a bien été ajouté !');

            // Supprime la variable de session photos
            $request->getSession()->remove('photos');

            return $this->redirectToRoute('add');
        }

        return $this->render('add/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
