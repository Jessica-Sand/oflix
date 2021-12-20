<?php

namespace App\Controller;

use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TvShowRepository $tvShowRepository): Response
    {
        // 3 latests TvShow
        $latestTvShow = $tvShowRepository->findBy([], ['title' => 'ASC'], 3);
        return $this->render('home/index.html.twig', [
            // findBy :
            // - critères vides
            // - order by title ASC
            // - limit = 3
            'latestTvShow' => $latestTvShow
        ]);
    }

    /**
     * @Route("/search", name="search")
     *
     * @return void
     */
    public function search(Request $request, TvShowRepository $tvShowRepository)
    {
        // On récupère l'information saisie dans le formulaire
        $searchValue = $request->get('query');

        // On effectue une recherche de séries basée sur $searchValue
        // $tvShows = $tvShowRepository->findSearchByTitleDQL($searchValue);
        $tvShows = $tvShowRepository->findSearchByTitle($searchValue);

        // On affiche le résultat dans une vue html
        return $this->render('home/search.html.twig', [
            'tvshows' => $tvShows,
            'searchValue' => $searchValue
        ]);
    }
}
