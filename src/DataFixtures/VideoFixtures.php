<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VideoFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $trick = $manager->find(Trick::class, 72);

        for($i = 1; $i <=10; $i++){
            $video = new Video();
            $video->setTrick($trick)
                  ->setUrl("http://placehold.it/350x350");

            $manager->persist($video);
        }

        $manager->flush();
    }
}
