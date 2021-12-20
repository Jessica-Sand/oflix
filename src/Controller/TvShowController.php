<?php

namespace App\Controller;

use App\Entity\TvShow;
use App\Repository\TvShowRepository;
use App\Service\MessageGenerator;
use App\Service\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tvshow", name="tvshow_")
 */
class TvShowController extends AbstractController
{
    /**
     * Listing de toutes les séries
     * 
     * URL : /tvshow/list , nom de la route : tvshow_list
     * 
     * @Route("/list", name="list")
     */
    public function index(TvShowRepository $tvShowRepository, MessageGenerator $messageGenerator): Response
    {
        $this->addFlash('success', $messageGenerator->randomMessage());

        return $this->render('tvshow/list.html.twig', [
            'tvshows' => $tvShowRepository->findAllOrderByTitle(),
        ]);
    }

    /**
     * Détails d'une série en fonction de son ID
     * 
     * Route : /tvshow/{id}
     * 
     * @deprecated version 1.1 Remplacé par details
     *
     * @Route("/{id}", name="details", requirements={"index" = "\d+"})
     * @return void
     */
    // public function details(TvShow $tvShow)
    public function show(int $id, TvShowRepository $tvShowRepository, OmdbApi $omdbApi)
    {
        // Il faut être connecté (ROLE_USER) pour afficher le détail d'une série
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Voici ce que fait le param converter
        // ==> $tvShow = $tvShowRepository->find($id)
        // ==> $tvShow == série dont l'id est égal à $id        
        // - Si la série existe, alors $tvShow possède les informations sur elle
        // - Si la série n'existe pas, le param converter va renvoyer une erreur 404
        /** @var TvShow  **/
        $tvShow = $tvShowRepository->findWithDetails($id);

        // $title = $tvShow->getTitle();
        // $tvShowData = $omdbApi->fetch($title);

        // dump($tvShowData);

        if ($tvShow === null) {
            // On affiche une 404
            // Que l'on pourra ensuite customiser : https://symfony.com/doc/current/controller/error_pages.html
            throw $this->createNotFoundException('Cette série n\'existe pas');
        }
        // dd($tvShow);
        // Le code HTTP"301 permet de faire une redirection permanente
        // On dit à ce moment que la page courante est amener à disparaitre
        // pour laisser place à la nouvelle
        return $this->redirectToRoute('tvshow_slug', ['slug' => $tvShow->getSlug()], 301);
    }

    /**
     * Route permettant l'affichage d'une série en fonction de son slug
     * 
     * @Route("/details/{slug}", name="slug", requirements={"slug"="[a-zA-Z1-9\-_\/]+"})
     * @return Response
     */
    public function details(TvShow $tvShow)
    {
        // $tvShow est la série dont le slug est égal à {slug}
        // On aurait pu également appeller la méthode findOneBy(['slug' => $slug])
        return $this->render('tvshow/details.html.twig', [
            'tvShow' => $tvShow
        ]);
    }
}
