<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Photo;

class PhotoFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $trick = $manager->find(Trick::class, 75);

        for($i = 1; $i <=10; $i++){
            $photo = new Photo();
            $photo->setTrick($trick)
                  ->setUrl("http://placehold.it/350x250");

            $manager->persist($photo);
        }

        $manager->flush();
    }
}
