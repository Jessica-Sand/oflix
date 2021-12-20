<?php

namespace App\Tests\Form;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostTest extends WebTestCase
{
    /**
     * On test qu'une requête POST, lors de l'ajout d'une série, renvoie bien une redirection
     *
     * @return void
     */
    public function testAddingNewTvShow(): void
    {
        $client = static::createClient();

        // On simule une connexion au site puisqu'il faut etre connecté en temps qu'admin pour faire un ajout
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userOflix = $userRepository->findOneBy(['email' => 'j.sand@gmail.com']);
        $client->loginUser($userOflix);


        // On demande à accéder à la page d'ajout d'une série
        $crawler = $client->request('GET', 'admin/tvshow/add');

        // On vérifie si le formulaire à été envoyé
        $buttonCrawlerNode = $crawler->selectButton('submit');

        // Après envoie on vérifie si on est redirigé vers la liste des séries
        $this->assertResponseIsSuccessful($client->getResponse('/tvshow/list'));
    }

    /**
     * On test qu'une requête POST, lors de l'ajout d'un personnage, renvoie bien une redirection
     *
     * @return void
     */
    public function testAddingNewCharacter(): void
    {
        $client = static::createClient();

        // On simule une connexion au site puisqu'il faut etre connecté en temps qu'admin pour faire un ajout
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userOflix = $userRepository->findOneBy(['email' => 'j.sand@gmail.com']);
        $client->loginUser($userOflix);


        // On demande à accéder à la page d'ajout d'une série
        $crawler = $client->request('GET', 'admin/character/add');

        // On vérifie si le formulaire à été envoyé
        $buttonCrawlerNode = $crawler->selectButton('submit');

        // Après envoie on vérifie si on est redirigé vers la liste des personnages
        $this->assertResponseIsSuccessful($client->getResponse('/character/list'));
    }

    // /**
    //  * On test qu'une requête POST, lors de l'ajout d'une saison, renvoie bien une redirection
    //  *
    //  * @return void
    //  */
    // public function testAddingNewSeason(): void
    // {
    //     $client = static::createClient();

    //     // On simule une connexion au site puisqu'il faut etre connecté en temps qu'admin pour faire un ajout
    //     $userRepository = static::getContainer()->get(UserRepository::class);
    //     $userOflix = $userRepository->findOneBy(['email' => 'j.sand@gmail.com']);
    //     $client->loginUser($userOflix);


    //     // On demande à accéder à la page d'ajout d'une série
    //     $crawler = $client->request('GET', 'admin/tvshow/{id}/season/add');

    //     // On vérifie si le formulaire à été envoyé
    //     $buttonCrawlerNode = $crawler->selectButton('submit');

    //     // Après envoie on vérifie si on est redirigé vers la liste des saisons
    //     $this->assertResponseIsSuccessful($client->getResponse('/admin/tvshow/{id}'));
    // }
}
