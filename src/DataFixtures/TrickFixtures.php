<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Trick;
use phpDocumentor\Reflection\Types\This;

class TrickFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $groupe = $manager->find(Group::class,543);
        $member = $manager->find(Member::class, 533);

        for($i = 1; $i <=10; $i++){
            $trick = new Trick();
            $trick->setTitle("Trick n°$i")
                  ->setContent("Contenu de l'article du post")
                  ->setCreatedAt(new \DateTime())
                  ->setGroupe($groupe)
                  ->setMainPhotoUrl("http://placehold.it/350x350")
                  ->setMemberCreator($member)
                  ->setUpdatedAt(null);
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
