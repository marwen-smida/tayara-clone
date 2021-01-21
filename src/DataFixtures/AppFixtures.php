<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\Utilisaleur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {





        $faker = Factory::create('FR-fr');
        $users = [];
        $genres = ['male', 'female'];
        for ($i=0; $i < 100; $i++) {

            $user = new Utilisaleur();

            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;
//            $hash = "123456";
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
            $manager->flush();
        }



        $k = 0;
        for ($i = 0 ; $i<=1000; $i++)
        {
            $user = $users[mt_rand(0,count($users) - 1)];

            $ad = new Ad();

            $title = $faker->sentence();
            $intro = $faker->paragraph(1);
            $description = $faker->paragraph(5);
            $imageUrl = "https://picsum.photos/800/450?image=". $k++;

            $ad->setPrice(mt_rand(0,200))
                ->setTitle($title)
                ->setIntro($intro)
                ->setDescription($description)
                ->setAuthor($user)
            ->setCoverImage($imageUrl);


            for ($j = 0; $j<mt_rand(3,8); $j++)
            {

                $imageUrl = "https://picsum.photos/800/450?image=". $k++;
                $image = new Image();
                $image->setUrl($imageUrl)
                    ->setAd($ad);
                $manager->persist($image);
                if ($k==1000){$k=0;}

            }
            $manager->persist($ad);




        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
