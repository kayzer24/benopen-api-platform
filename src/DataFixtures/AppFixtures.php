<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email())
//                ->setRoles(['ROLE_USER'])
                ->setPassword($this->passwordHasher->hashPassword($user, 'password'))
//                ->setUpdatedAt(new DateTimeImmutable())
            ;

            $manager->persist($user);

            for ($a = 0; $a < rand(5, 15); $a++) {
                $article = (new Article())
                    ->setName($faker->text(50))
                    ->setContent($faker->text(300))
                    ->setAuthor($user)
                    ;

                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}
