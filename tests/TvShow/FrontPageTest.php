<?php

namespace App\Tests\TvShow;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontPageTest extends WebTestCase
{
    /**
     * On va s'assurer que les personnes non connectée
     * sont redirigées vers la page de login
     * 
     * testRedirectWhenNotLoggedIn : copy Aimerick + Listeria
     *
     * @return void
     */
    public function testRedirectWhenNotLoggedIn(): void
    {
        // On simule l'accès à la liste des séries
        $client = static::createClient();
        $crawler = $client->request('GET', '/tvshow/list');

        // Puisque je ne suis pas connecté, je devrais
        // en principe être redirigé vers la page de login
        $this->assertResponseRedirects('/login');
    }

    /**
     * On vérifie que le fait que l'on est accès à la page
     * /tvshow/list quand on est connecté
     *
     * @return void
     */
    public function testAccessWhenLoggedIn()
    {
        $client = static::createClient();

        // On simule une connexion au site
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userOflix = $userRepository->findOneBy(['email' => 'j.sand@gmail.com']);
        $client->loginUser($userOflix);

        // On simule l'accès à la liste des séries
        $crawler = $client->request('GET', '/tvshow/list');

        // On vérifie que l'on a accès à la page /tvshow/list
        $this->assertResponseIsSuccessful();
    }
}