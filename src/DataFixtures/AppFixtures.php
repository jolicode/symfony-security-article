<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new Admin();
        $admin->setName('greg');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $admin = new Admin();
        $admin->setName('alice');
        $admin->setRoles(['ROLE_EDITOR', 'ROLE_ARTICLE_CATEGORY_PHP']);
        $manager->persist($admin);

        $manager->flush();
    }
}
