<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;

class TagFixture extends BaseFixture
{

    protected function loadData(ObjectManager $em)
    {
        $this->createMany(\App\Entity\Tag::class, 10, function(\App\Entity\Tag $tag) {
            $tag->setName($this->faker->realText(20));
        });

        $em->flush();

    }
}
