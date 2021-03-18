<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Member;
use App\Entity\Photo;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $trick = $manager->find(Trick::class, 72);
        $member = $manager->find(Member::class, 533);

        for($i = 1; $i <=10; $i++){
            $comment = new Comment();
            $comment->setTrick($trick)
                    ->setMemberCreator($member)
                    ->setCreatedAt(new \DateTime())
                    ->setComment("Super figure, j'adore !");

            $manager->persist($comment);
        }

        $manager->flush();
    }
}
