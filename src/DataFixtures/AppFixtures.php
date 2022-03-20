<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const DEFAULT_USER = ['email' => 'kayzer24@test.fr', 'password' => 'password'];

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $defaultUser = new User();
        $defaultUser->setEmail(self::DEFAULT_USER['email'])
            ->setPassword($this->passwordHasher->hashPassword($defaultUser, self::DEFAULT_USER['password']));

        $manager->persist($defaultUser);

        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword($this->passwordHasher->hashPassword($user, 'password'));

            if (0 === $i % 3) {
                $user->setStatus(false)
                    ->setAge(23);
            }

            $manager->persist($user);

            for ($a = 0; $a < rand(5, 15); ++$a) {
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
