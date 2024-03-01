<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Keyboard;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    )
    {
    }

    /**
     * @throws \Exception
     */ 
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <=10; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@example.com');
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    'password'
                )
            );

            if ($i == 1) {
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }

            $manager->persist($user);
        }

        // Générer des claviers
        for ($i = 1; $i <= 20; $i++) {
            $keyboard = new Keyboard();
            $keyboard->setName('Keyboard-' . $i);
            $keyboard->setPrice(mt_rand(50, 900));
            $keyboard->setDescription('Description for keyboard ' . $i);
            $keyboard->setStock(mt_rand(10, 100));
            $keyboard->setSlug('Keyboard-' . $i . '-' . uniqid());

            $manager->persist($keyboard);
        }
        $manager->flush();
    }
}
