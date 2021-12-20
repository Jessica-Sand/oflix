<?php

namespace App\Service;

class MessageGenerator
{
    /**
     * Service retournant une citation aléatoire
     *
     * @return string
     */
    public function randomMessage(): string
    {
        $quotesArray = [
            'Qui ne tente rien n\'a rien', // index 0
            'Ici c\'est PARIS!', // index 1
            'Un service c\'est pour te rendre un service', // index 2
            "un anneau pour les gouverner tous", // index 3
            "C'est pas faux !", // index 4
            "Si on peut on reporte à demain...", // index 5
            "la parole est d'argent mais le silence est d'or", // index 6
            "ce qui ne me tue pas me rends plus fort", // index 7
            "c est en forgeant....#CharlesOclock", // index 8
            "La réponse D PLus tard", // index 9
            "Je suis TON PAIR...programming", // index 10
            "Ils ne savaient pas que c'était impossible, alors ils l'ont fait", // index 11
            "quand t'es content, tu commit", // index 12
            "#titanic { float:none}" // index 13
        ];

        // Retourne un index aléatoire à partir du tableau $quotesArray
        $randomIndex = array_rand($quotesArray);

        return $quotesArray[$randomIndex];
    }
}
