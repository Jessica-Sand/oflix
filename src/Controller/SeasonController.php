<?php

namespace App\Controller;

use App\Entity\Season;
use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 
 * @Route("/season", name="season_")
 */
class SeasonController extends AbstractController
{
    /**
     * 
     * Affiche les épisodes d'une saison
     * 
     * @param int $id : Identifiant de la saison
     * 
     * @Route("/{id}", name="season_episodes")
     * 
     * Ex : /season/46
     */
    public function show(Season $season): Response
    {
        // Saison dont l'id est 46
        dump($season);

        // Le titre de la série associée à la saison courante
        // 1 saison ne peut appartenir qu'à une seule série ;-)
        dd($season->getTvShow()->getTitle());
    }
}
