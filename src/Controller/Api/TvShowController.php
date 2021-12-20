<?php

namespace App\Controller\Api;

use App\Entity\TvShow;
use App\Form\TvShowType;
use App\Repository\TvShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/tvshows", name="api_tvshows_")
 */
class TvShowController extends AbstractController
{
    /**
     * Retourne toutes les séries du site
     * 
     * API : GET /api/v1/tvshows
     * 
     * @Route("", name="list", methods={"GET"})
     */
    public function index(TvShowRepository $tvShowRepository): Response
    {
        $tvShows = $tvShowRepository->findAll();

        // Le serializer de Symfony n'ira chercher que des données
        // taggées avec le group TVSHOWS
        // Arguments de la méthode json
        // $data ==> Données à sérialiser (transformer en JSON)
        // int $status = 200    ==> Code HTTP (200, 201, ...401,403, 404...)
        // array $headers = []  ==> Si l'on souhaite modifier une entete HTTP
        // array $context = []  ==> Permet de donner un peu de contexte au Serializer
        // pour l'aider à gérer les cas ou il y a des relations 
        // (un tvshow => character => tvshow => character ...Erreur ! Reference circulaire)

        return $this->json($tvShows, 200, [], [
            'groups' => 'tvshows'
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * 
     * API : /api/v1/tvshows/{id}
     * 
     * Retourne un Tvshow en fonction de son ID
     */
    public function show(TvShow $tvShow)
    {
        return $this->json($tvShow, 200, [], [
            'groups' => 'tvshows'
        ]);
    }

    /**
     * Crée une nouvelle série à partir d'information
     * en provenance d'une application extérieur (React, Appli mobile, ...)
     * 
     * @Route("", name="add", methods={"POST"})
     * 
     * API : POST /api/v1/tvshows
     *
     * @return void
     */
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        // On récupère du texte en JSON
        $JsonData = $request->getContent();

        // On va ensuite transformer notre JSON en objet
        // C'est ce que l'on appelle la Désérialisation
        // JSON => OBJECT
        // La méthode deserialize va transformer les données JSON
        // en objet TvShow
        $tvshow = $serializer->deserialize($JsonData, TvShow::class, 'json');

        // On vérifie que tous les critères de validation de l'entité
        // TVShow sont respectés (Assert\NotBlank, ...)
        // https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($tvshow);

        if (count($errors) > 0) {
            // On a au moins une erreur détectée
            $errorsString = (string) $errors;
            return $this->json(
                [
                    'error' => $errorsString
                ],
                500
            );
        } else {
            // On a pas d'erreur...on peut sauvegarder
            // On appelle manager pour sauvegarder
            $em = $this->getDoctrine()->getManager();
            $em->persist($tvshow);
            $em->flush();

            // On retourne une réponse clair au client (React, appli mobile, Insomnia, ..)
            return $this->json(
                [
                    'message' => 'La série ' . $tvshow->getTitle() . ' a bien été créé'
                ],
                201 // 201 - Created https://developer.mozilla.org/fr/docs/Web/HTTP/Status/201
            );
        }
    }

    /**
     * @Route("/{id}", name="update", methods={"PUT|PATCH"})
     * 
     * API : /api/v1/tvshows/{id}
     *
     * @return void
     */
    public function update(TvShow $tvShow, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $jsonData = $request->getContent();

        // Le serializer permet, grace à la méthode deserialize,
        // de transformer le json reçu depuis insomnia (ou front)
        // en objet de la classe TvShow.
        // L'objet $tvshow sera après deserialisation une version
        // fusionnée des données issues du front + les données déjà présentes
        // en base de données
        $tvShow = $serializer->deserialize($jsonData, TvShow::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $tvShow]);

        $errors = $validator->validate($tvShow);

        if (count($errors) == 0) {
            // On a pas d'erreur...On sauvegarde l'entité
            $this->getDoctrine()->getManager()->flush();

            return $this->json([
                'message' => 'La série a bien été mise à jour'
            ]);
        }

        // dd($errors);

        // 400 : code d'erreur BAD request
        // à retourner si le client a mal formatée sa requete
        // $errors est un tableau et comme on ne peut pas echo un tableau on va le renvoyer en string
        return $this->json([
            'errors' => (string) $errors
        ], 400);
    }


    /**
     * Suppression d'une série en BDD
     *
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * 
     * @return Response
     */
    public function delete(TvShow $tvShow)
    {
        $this->em->remove($tvShow);
        $this->em->flush();

        // Code 204 : https://developer.mozilla.org/fr/docs/Web/HTTP/Status/204
        return $this->json('', 204);
    }
}
