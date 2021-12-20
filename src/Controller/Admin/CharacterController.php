<?php

namespace App\Controller\Admin;

use App\Entity\Character;
use App\Form\Type\CharacterType;
use App\Repository\CharacterRepository;
use App\Service\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/character", name="admin_character_")
 */
class CharacterController extends AbstractController
{
     /**
     * 
     * URL : /admin/character/list , nom de la route : admin_character_list
     * 
     * @Route("/list", name="list")
     */
    public function index(CharacterRepository $characterRepository): Response
    {
        return $this->render('admin/character/index.html.twig', [
            'characters' => $characterRepository->findAll(),
        ]);
    }

    /**
     * 
     * @Route("/add", name="add")
     * 
     * @return void
     */
    public function add(Request $request, ImageUploader $imageUploader )
    {
        $character = new Character();

        $form = $this->createForm(CharacterType::class, $character);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newFileName = $imageUploader->upload($form, 'image');

            // On met à jour le chemin vers l'image en BDD
            $character->setPictureFilename($newFileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($character);
            $em->flush();

            $this->addFlash('success', 'Personnage ajouté avec succès');

            return $this->redirectToRoute('admin_character_list');
        }

        return $this->render('admin/character/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/{id}", name="show", requirements={"index" = "\d+"})
     * @return Response
     */
    public function show(Character $character)
    { 
        return $this->render('admin/character/show.html.twig', [
            'character' => $character
        ]);
    }

    /**
     *
     * @Route("/edit/{id}", name="edit", requirements={"index" = "\d+"})
     * @return void
     */
    public function edit(Character $character, Request $request, ImageUploader $imageUploader )
    {
        $form = $this->createForm(CharacterType::class, $character);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newFileName = $imageUploader->upload($form, 'image');

            // On met à jour le chemin vers l'image en BDD
            $character->setPictureFilename($newFileName);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Mise à jour du personnage effectuée');

            return $this->redirectToRoute('admin_character_list');
        }

        return $this->render("admin/character/edit.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/delete/{id}", name="delete", requirements={"index" = "\d+"})
     * @return Response
     */
    public function delete(Character $character, Request $request)
    {
        $submitedToken = $request->get('token');

        if ($this->isCsrfTokenValid('delete-character', $submitedToken)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($character);
            $entityManager->flush();

            $this->addFlash('success', 'Le personnage a bien été supprimé');

            return $this->redirectToRoute('admin_character_list');
        } else {
            return new Response('Action interdite', 403);
        }
    }
}