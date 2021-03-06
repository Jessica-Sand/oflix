<?php

namespace App\Controller\Admin;

use App\Entity\Character;
use App\Entity\Season;
use App\Entity\TvShow;
use App\Form\SeasonType;
use App\Form\TvShowType;
use App\Form\Type\CharacterType;
use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/admin/tvshow", name="admin_tvshow_", requirements={"index" = "\d+"})
 */
class TvShowController extends AbstractController
{
    /**
     * 
     * URL : /admin/tvshow/list , nom de la route : admin_tvshow_list
     * 
     * @Route("/list", name="list")
     */
    public function index(TvShowRepository $tvShowRepository): Response
    {
        return $this->render('admin/tvshow/index.html.twig', [
            'tvshows' => $tvShowRepository->findAll(),
        ]);
    }

    /**
     * 
     * @Route("/add", name="add")
     * 
     * @return void
     */
    public function add(Request $request, SluggerInterface $slugger)
    {
        $tvShow = new TvShow();

        $form = $this->createForm(TvShowType::class, $tvShow);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($tvShow);

            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
               
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $tvShow->setImageurl($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($tvShow);
            $em->flush();

            // Message flash
            $this->addFlash('success', 'La s??rie ' . $tvShow->getTitle() . ' a bien ??t?? ajout??e');

            return $this->redirectToRoute('tvshow_list');
        }

        return $this->render('admin/tvshow/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/{id}", name="show")
     * @return void
     */
    public function show(int $id, TvShowRepository $tvShowRepository)
    {
        $tvshow = $tvShowRepository->find($id);  

        // dd($tvshow);

        return $this->render('admin/tvshow/show.html.twig', [
            'tvshow' => $tvshow
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(TvShow $tvShow, Request $request)
    {
        $form = $this->createForm(TvShowType::class, $tvShow);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'La s??rie ' . $tvShow->getTitle() . ' a bien ??t?? mise ?? jour');

            return $this->redirectToRoute('admin_tvshow_show', ['id' => $tvShow->getId()]);
        }

        return $this->render('admin/tvshow/edit.html.twig', [
            'form' => $form->createView(),
            'tvShow' => $tvShow
        ]);
    }

    /**
     *
     * @Route("/{id}", name="update")
     * @return Response
     */
    public function update(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tvshow = $entityManager->getRepository(TvShow::class)->find($id);

        // dd($tvshow);

        if (!$tvshow) {
            throw $this->createNotFoundException(
                'No tvshow found for id '.$id
            );
        }

        $tvshow->setTitle('New tvshow title!');
        $entityManager->flush();

        return $this->redirectToRoute('admin_tvshow_show', [
            'id' => $tvshow->getId()
        ]);
    }

    /**
     *
     * @Route("/{id}", name="delete")
     * @return Response
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tvshow = $entityManager->getRepository(TvShow::class)->find($id);

        // dd($tvshow);

        if (!$tvshow) {
            throw $this->createNotFoundException(
                'No tvshow found for id '.$id
            );
        }

        $entityManager->remove($tvshow);
        $entityManager->flush();

        return $this->redirectToRoute('admin_tvshow_list', [
            'id' => $tvshow->getId()
        ]);
    }

    /**
     * @Route("/{id}/season/add", name="season_add")
     * 
     * URL : /admin/tvshow/{id}/season/add
     * 
     * {id} ==> L'identifiant de la s??rie
     * 
     * PSR-12
     *
     * @return void
     */
    public function addNewSeason(TvShow $tvShow, Request $request)
    {
        $season = new Season();

        $form = $this->createForm(SeasonType::class, $season);

        // On r??cup??re les donn??es du formulaire que l'on inhjectera
        // dans l'objet $season
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On associe la saison $season ?? notre s??rie
            $tvShow->addSeason($season);

            $em = $this->getDoctrine()->getManager();

            // La saison n'existe pas encore en BDD, on la fait
            // donc persister (on la rajoute ?? la liste d'entit??s que Doctrine devra g??rer)
            $em->persist($season);

            // On sauvegarde les donn??es en BDD
            // - On cr??e la saison
            // - On met ?? jour la s??rie (on lui associe la nouvelle saison)
            $em->flush();

            // Message de success
            $this->addFlash('success', "La saison num??ro {$season->getSeasonNumber()} a bien ??t?? associ??e ?? la s??rie {$tvShow->getTitle()}");

            // On redirige vers la vue d??taill??e d'une s??rie
            return $this->redirectToRoute('admin_tvshow_show', ['id' => $tvShow->getId()]);
        }

        return $this->render('admin/tvshow/season_add.html.twig', [
            'form' => $form->createView(),
            'tvShow' => $tvShow
        ]);
    }

    /**
     * @Route("/{id}/character/add", name="character_add")
     * 
     * URL : /admin/tvshow/{id}/character/add
     * 
     * {id} ==> L'identifiant de la s??rie
     * 
     * PSR-12
     *
     * @return void
     */
    public function addNewCharacter(TvShow $tvShow, Request $request)
    {
        $character = new Character();

        $form = $this->createForm(CharacterType::class, $character);

        // On r??cup??re les donn??es du formulaire que l'on injectera
        // dans l'objet $character
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On associe le personnage $character ?? notre s??rie
            $tvShow->addCharacter($character);

            $em = $this->getDoctrine()->getManager();

            // La saison n'existe pas encore en BDD, on la fait
            // donc persister (on la rajoute ?? la liste d'entit??s que Doctrine devra g??rer)
            $em->persist($character);

            // On sauvegarde les donn??es en BDD
            // - On cr??e la saison
            // - On met ?? jour la s??rie (on lui associe la nouvelle saison)
            $em->flush();

            // Message de success
            $this->addFlash('success', "Le personnage {$character->getFirstname()} a bien ??t?? associ??e ?? la s??rie {$tvShow->getTitle()}");

            // On redirige vers la vue d??taill??e d'une s??rie
            return $this->redirectToRoute('admin_tvshow_show', ['id' => $tvShow->getId()]);
        }

        return $this->render('admin/tvshow/character_add.html.twig', [
            'form' => $form->createView(),
            'tvShow' => $tvShow
        ]);
    }
}