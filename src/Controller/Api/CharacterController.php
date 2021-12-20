<?php

namespace App\Controller\Api;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/v1/characters", name="api_characters_")
 */
class CharacterController extends AbstractController
{
    /**
     * Retourne touts les personnages du site
     * API : GET /api/v1/characters
     * 
     * @Route("", name="list", methods={"GET"})
     * 
     **/
    public function index(CharacterRepository $characterRepository): Response
    {
        $characters = $characterRepository->findAll();

        return $this->json($characters, 200, [], [
            'groups' => 'characters'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * 
     * API : /api/v1/characters/{id}
     * 
     * Retourne un personnage en fonction de son ID
     */
    public function show(Character $character)
    {
        return $this->json($character, 200, [], [
            'groups' => 'characters'
        ]);
    }

    /**
     * Suppression d'un personnage en BDD
     *
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * 
     * @return Response
     */
    public function delete(Character $character)
    {
        $this->em->remove($character);
        $this->em->flush();

        // Code 204 : https://developer.mozilla.org/fr/docs/Web/HTTP/Status/204
        return $this->json('', 204);
    }
}
