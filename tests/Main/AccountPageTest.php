<?php

namespace App\Tests\Main;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountPageTest extends WebTestCase
{
    /**
     * Vérification de l'accès à la page de login
     * pour les personnes non connectées (anonymes)
     *
     * @return void
     */
    public function testLoginPage(): void
    {
        // On simule l'accès à la page de login
        // via un client http
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // On vérifie que l'on a bien accès à la page de login
        $this->assertResponseIsSuccessful();

        // On vérifie également la présence d'une balise h1 
        // contenant le texte "Se connecter"
        // Ici on vérifie 2 assertions (@copyright 2021 Viviane, fanou & Pixniel)
        // 1) la présence de la balise h1
        // 2) la présence du texte Se connecter
        $this->assertSelectorTextContains('h1.h3.mb-3', 'Se connecter');
    }

    /**
     * Vérification de l'accès à la page d'inscription
     * aux personnes non connectées
     *
     * @return void
     */
    public function testRegisterPage()
    {
        // On simule l'accès à la page d'inscription
        // via un client http
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        // On vérifie que la page est bien accessible
        $this->assertResponseIsSuccessful();

        // On vérifie également la présence d'une balise h1 
        // contenant le texte "Nouveau compte"
        $this->assertSelectorTextContains('h1', 'Nouveau compte');
    }
}
