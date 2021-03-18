<?php

namespace App\DataFixtures;

use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Group;

class GroupFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <=10; $i++){
            $group = new Group();
            $group->setName("Groupe n°$i");

            $manager->persist($group);
        }

        $manager->flush();
    }
}
