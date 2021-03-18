<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Member;

class MemberFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        for($i = 1; $i <=10; $i++){
            $member = new Member();
            $member->setUsername("Utilisateur n°$i")
                   ->setPassword("TestMDP")
                   ->setEmail("test@test.com")
                   ->setCreatedAt(new \DateTime())
                   ->setUrlAvatar("http://placehold.it/350x350");
            $manager->persist($member);
        }

        $manager->flush();
    }
}
