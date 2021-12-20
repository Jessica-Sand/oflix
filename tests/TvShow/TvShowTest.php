<?php

namespace App\Tests\TvShow;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TvShowTest extends WebTestCase
{
    /**
     * On vérifie que le fait que l'on est accès à la page
     * admin/tvshow/list quand on est connecté en temps qu'utilisateur ordinaire
     *
     * @return void
     */
    public function testLessAccessWhenLoggedIn()
    {
        $client = static::createClient();

        // On simule une connexion au site
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userOflix = $userRepository->findOneBy(['email' => 'john_doe@gmail.com']);
        $client->loginUser($userOflix);

        // On simule l'accès à la liste des séries
        $crawler = $client->request('GET', 'admin/tvshow/list');

        // On vérifie que l'on a accès à la page admin/tvshow/list mais comme on n'a pas accès on renvoi une erreur 403
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    /**
     * On vérifie que le fait que l'on est accès à la page
     * admin/tvshow/list quand on est pas connecté
     *
     * @return void
     */
    public function noAccessWhenNotLoggedIn()
    {
        // On simule l'accès à la liste des séries côté admin
        $client = static::createClient();
        $crawler = $client->request('GET', 'admin/tvshow/list');

        // Comme l'internaute n'est pas connecté il n'a pas accès on renvoi une erreur 302
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * On vérifie que le fait que l'on est accès à la page
     * admin/tvshow/list quand on est connecté
     *
     * @return void
     */
    public function testWhenLoggedIn()
    {
        $client = static::createClient();

        // On simule une connexion au site
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userOflix = $userRepository->findOneBy(['email' => 'j.sand@gmail.com']);
        $client->loginUser($userOflix);

        // On simule l'accès à la liste des séries
        $crawler = $client->request('GET', 'admin/tvshow/list');

        // Comme l'internaute est connecté il a accès on renvoi une 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
