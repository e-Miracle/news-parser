<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = new User();
        $users->setRoles(['ROLE_ADMIN']);
        $users->setEmail('admin@test.com');
        $users->setPassword(password_hash('12345678', PASSWORD_DEFAULT, ['cost'=>13]));

        $manager->persist($users);
        $manager->flush();

        $users = new User();
        $users->setRoles([]);
        $users->setEmail('moderator@test.com');
        $users->setPassword(password_hash('12345678', PASSWORD_DEFAULT, ['cost'=>13]));

        $manager->persist($users);
        $manager->flush();
    }
}
