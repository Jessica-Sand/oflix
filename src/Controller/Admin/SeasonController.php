<?php

namespace App\Controller\Admin;

use App\Entity\Episode;
use App\Entity\Season;
use App\Form\EpisodeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/season", name="admin_season_", requirements={"id":"\d+"})
 */
class SeasonController extends AbstractController
{
    /**
     * Affiche les détails d'une saison
     * 
     * @Route("/{id}", name="details")
     */
    public function details(Season $season): Response
    {
        return $this->render('admin/season/details.html.twig', [
            'season' => $season,
        ]);
    }

    /**
     * @Route("/{id}/episode/add", name="episode_add")
     *
     * @return void
     */
    public function addNewEpisode(Season $season, Request $request)
    {
        $episode = new Episode();

        $form = $this->createForm(EpisodeType::class, $episode);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($episode);

            $season->addEpisode($episode);

            $em->flush();

            $this->addFlash('success', "L'épisode {$episode->getTitle()} a bien été associée à la série {$season->getTvShow()->getTitle()}");

            return $this->redirectToRoute('admin_season_details', ['id' => $season->getId()]);
        }

        return $this->render('admin/season/add_episode.html.twig', [
            'form' => $form->createView(),
            'season' => $season
        ]);
    }
}
