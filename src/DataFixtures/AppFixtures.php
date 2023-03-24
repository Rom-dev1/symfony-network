<?php

namespace App\DataFixtures;

use App\Entity\Comment;
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
            $user->setPassword($this->hasher->hashPassword($user, "password")); //@todo rmettre le hasher $this->hasher->hashPassword($user, $faker->word())
            $user->setRoles(['ROLE_USER']);
            $user->setFirstname($faker->firstName($gender[array_rand($gender)]));
            $user->setUsername($faker->lastName());
            $user->setAvatar("uploads/avatar/photoprofil.jpg");
            $user->setDateOfBirthAt(\DateTimeImmutable::createFromMutable($faker->dateTimeInInterval("-30 years", "+10 years")));
            $user->setBiography($faker->text());
            
            for($j = 0; $j < rand(2, 4); $j++){
                $post = new Publication();
                $post->setContent($faker->paragraph(rand(3, 10)));
                $post->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 week', '-1day')));
                $post->setUser($user);
                $manager->persist($post);

                for($k = 0; $k < rand(2,4); $k++){
                    $comment = new Comment();
                    $comment->setContent($faker->text());
                    $comment->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('$post->getCreatedAt', 'now')));
                    $comment->setPublication($post);
                    $comment->setUser($user);
                    $manager->persist($comment);
                }
            }
            $manager->persist($user);
        }
        $manager->flush();
    }
}
