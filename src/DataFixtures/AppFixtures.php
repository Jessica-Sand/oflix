<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Character;
use App\Entity\Season;
use App\Entity\TvShow;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    private $slugger;
    public function __construct(UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger)
    {
        $this->passwordHasher = $passwordHasher;
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager)
    {
        // Instanciation du Faker
        $faker = Faker\Factory::create('fr_FR');

        // create 20 characters! Bam!
        $charactersList = [];
        for ($i = 0; $i < 20; $i++) {
            $character = new Character();
            $character->setFirstname($faker->firstName());
            $character->setLastname($faker->lastName());
            $character->setGender(mt_rand(0, 1) === 0 ? 'Homme' : 'Femme');
            $charactersList[] = $character;
            $manager->persist($character);
        }
        $charactersListTotal = count($charactersList);

        $categoriesList = [
            'Comédie',
            'Action',
            'Science fiction',
            'Fantastique',
            'Policier',
            'Space Opéra',
            'Cyber Punk'
        ];
        $categoriesListTotal = count($categoriesList);

        $tvShowList = [
            'Dark',
            'Scrubs',
            'Dr WHO',
            'Dexter',
            'Friends',
            'Breaking Bad',
            'Ozark',
            'Better call saul',
            'Sherlock holmes',
            'The big bang theory',
            'Tchernobyl',
            'The Innocent',
            'GOT',
            'Mad Men',
            "The handmaid's tale"
        ];

        // create 20 tvshow ! Bam!
        foreach ($tvShowList as $currentTvShow) {
            // for ($i = 1; $i <= 20; $i++) {
            // On crée une nouvelle série
            $tvShow = new TvShow();

            // On configure avec les bonnes données
            $tvShow->setTitle($currentTvShow);
            $tvShow->setSynopsis($faker->realText());
            $tvShow->setNbLikes($faker->numberBetween(111111, 999999));

            // On assigne une catégorie à la série
            $category = new Category();
            $category->setName($categoriesList[mt_rand(0, $categoriesListTotal - 1)]);
            $tvShow->addCategory($category);

            // On assigne 2 personnages à la série (Possible qu'il y 2 fois le même personnages à cause de mt_rand)
            $tvShow->addCharacter($charactersList[mt_rand(0, $charactersListTotal - 1)]);
            $tvShow->addCharacter($charactersList[mt_rand(0, $charactersListTotal - 1)]);


            // On créé de nouvelles saisons que l'on associe à tvShow
            for ($seasonNumber = 1; $seasonNumber <= 3; $seasonNumber++) {
                $seasonObj = new Season();
                $seasonObj->setSeasonNumber($seasonNumber);
                $tvShow->addSeason($seasonObj);

                $manager->persist($seasonObj);
            }

            // On inclut les données dans la liste d'attente
            $manager->persist($category);
            $manager->persist($tvShow);
        }

        $adminUser = new User();
        $adminUser->setEmail('j.sand@gmail.com');
        $adminUser->setFirstname('Jess');
        $adminUser->setLastname('Sand');
        $adminUser->setRoles(['ROLE_SUPER_ADMIN']);
        $adminUser->setPassword($this->passwordHasher->hashPassword(
            $adminUser,
            'demo123'
        ));

        $manager->persist($adminUser);

        $adminUser2 = new User();
        $adminUser2->setEmail('john_doe@gmail.com');
        $adminUser2->setFirstname('John');
        $adminUser2->setLastname('Doe');
        $adminUser2->setRoles(['']);
        $adminUser2->setPassword($this->passwordHasher->hashPassword(
            $adminUser2,
            'demo123'
        ));
        
        $manager->persist($adminUser2);


        // On enregistre les personnages en BDD
        $manager->flush();
    }
}
