<?php

namespace App\Controller;

use App\DTO\TrickDTO;
use App\Entity\Photo;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\AddType;
use App\Form\TrickDTOType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    /**
     * @Route("/delete-video-{trickId}-{id}", name="delete-video")
     */
    public function deletevideo(Video $video, EntityManagerInterface $entityManager, $trickId){

        $entityManager->remove($video);
        $entityManager->flush();

        return $this->redirectToRoute('update-trick',['id'=>$trickId]);
    }

    /**
     * @Route("/delete-photo-{trickId}-{id}", name="delete-photo")
     */
    public function deletephoto(Photo $photo, EntityManagerInterface $entityManager, $trickId){

        $entityManager->remove($photo);
        $entityManager->flush();

        return $this->redirectToRoute('update-trick',['id'=>$trickId]);
    }

    /**
     * @Route("/update-trick-{id}", name="update-trick")
     * @param Trick $trick
     * @return Response
     */
    public function updateTrick(Trick $trick, TrickRepository $trickRepository, Request $request, EntityManagerInterface $entityManager): Response{

        $form = $this->createForm(TrickType::class, $trick); //transvase les données de la requête dans le DTO (formbinding)
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){

            $entityManager->flush();
            $this->addFlash('Notification', 'La figure a bien été modifié !');

            return $this->redirectToRoute('update-trick',['id'=>$trick->getId()]);
        }


        return $this->render('trick/update-trick.html.twig', [
            'trick' => $trick,
            'form'=>$form->createView()
        ]);


    }

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
     * @Route("/add-trick", name="add-trick")
     */
    public function addTrick(Request $request, EntityManagerInterface $entityManager, TrickRepository $trickRepository): Response
    {
        $dto = new TrickDTO($trickRepository);
        $form = $this->createForm(TrickDTOType::class, $dto);
        $form->handleRequest($request); //transvase les données de la requête dans le DTO


        if($form->isSubmitted() and $form->isValid()){

            $trick = new Trick();

            // Persiste le trick
            $file = $form['mainPhotoUrl']->getData();
            $fileName = uniqid('photo_').'.jpg';
            $file->move('img/upload', $fileName);

            $trick->setTitle($dto->getTitle());
            $trick->setContent($dto->getContent());
            $trick->setGroupe($dto->getGroupe());
            $trick->setMainPhotoUrl($fileName);
            $trick->setMemberCreator($this->getUser());
            $trick->setCreatedAt(new \DateTime());
            $entityManager->persist($trick);

            // Persiste les photos upload d'on le nom est en session
            $photos = $request->getSession()->get('photos', []);
            foreach($photos as $photo){
                $p = new Photo();
                //fait la relation entre les deux tables
                $p->setTrick($trick);
                $p->setUrl($photo);

                $entityManager->persist($p);
            }

            //Persiste les vidéos
            $strVideo = $dto->getVideoUrls();
            $videos = explode(',', $strVideo);
            foreach($videos as $video){
                $v = new Video();
                //fait la relation entre les deux tables
                $v->setTrick($trick);
                $v->setUrl($video);

                $entityManager->persist($v);
            }

            $entityManager->flush();
            $this->addFlash('Notification', 'Votre figure a bien été publié !');

            // Supprime la variable de session photos
            $request->getSession()->remove('photos');

            return $this->redirectToRoute('home');
        }

        return $this->render('trick/add-trick.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
