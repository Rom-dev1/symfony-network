<?php

namespace App\DataFixtures;

use App\Entity\Publication;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{   
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        $gender = [null, 'male', 'female'];
        
        for($i = 0; $i <5; $i++){
            $user = new User();
            $user->setEmail($faker->word().'@mail.com');
            $user->setPassword($faker->word()); //$this->hasher->hashPassword($user, $faker->word())
            $user->setRoles(['ROLE_USER']);
            $user->setFirstname($faker->firstName($gender[array_rand($gender)]));
            $user->setUsername($faker->lastName());
            $user->setAvatar('image.png');
            $user->setDateOfBirthAt(\DateTimeImmutable::createFromMutable($faker->dateTimeInInterval("-30 years", "+10 years")));
            $user->setBiography($faker->text());
            

            for($j = 0; $j <2; $j++){
                $post = new Publication();
                $post->setContent($faker->paragraph(rand(1, 3)));
                $post->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 week', 'now')));
                $post->setUser($user);
                $manager->persist($post);
            }
            
            $manager->persist($user);
        }

        
        $manager->flush();
    }
}
