<?php

namespace App\Tests\Main;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    /**
     * On test que l'on a accès à la page d'accueil
     * quand on est pas connecté
     *
     * @return void
     */
    public function testHomePagePublic(): void
    {
        // On instancie la classe KernelBrowser
        // pour simuler l'accès à une page web comme le 
        // ferait un navigateur
        $client = static::createClient();

        // On demande à accéder à la page d'accueil en GET
        $crawler = $client->request('GET', '/');

        // On vérifie si la page est bien accessible
        $this->assertResponseIsSuccessful(); // Code HTTP 200

        // On vérifie que dans la page d'accueil, on a une balise h1 
        // contenant le texte 'Séries TV et bien plus en illimité.'
        $this->assertSelectorTextContains('h1.fw-light', 'Séries TV et bien plus en illimité.');
    }

    /**
     * Méthode de tester que le clic sur le bouton "Se connecter"
     * nous redirige bien vers la page de login ("/login")
     *
     * @return void
     */
    public function testLoginButton()
    {
        $client = static::createClient();

        // On demande à accéder à la page d'accueil en GET
        $crawler = $client->request('GET', '/');

        // On simule un clic sur le bouton "Se connecter"
        // Après le clic on atterit sur la page de login
        $client->clickLink('Se connecter');

        // On vérifie donc que la page que l'on consulte (la page de login)
        // existe bien
        $this->assertResponseIsSuccessful();
    }
}
