<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/v1/categories", name="api_categories_")
 */
class CategoryController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    /**
     * Retourne toutes les catégories du site
     * 
     * API : GET /api/v1/categories
     * 
     * @Route("", name="list", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->json($categories, 200, [], [
            'groups' => 'categories'
        ]);

        //dd('hello');
    }

    /**
     * Retourne une catégorie en fonction de son ID
     * 
     * API : GET /api/v1/categories/{id}
     * 
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Category $category)
    {
        return $this->json($category, 200, [], [
            'groups' => 'categories'
        ]);
    }

    /**
     * Ajouter une nouvelle catégorie à partir d'information en provenance d'une application extérieur
     * 
     * API : POST /api/v1/categories
     * 
     * @Route("", name="add", methods={"POST"}) 
     * 
     * @return void
     */
    public function add(Request $request, SerializerInterface $serializer)
    {
        // On récupère du texte en JSON
        $JsonData = $request->getContent();

        // On va ensuite transformer notre JSON en objet
        $categorie = $serializer->deserialize($JsonData, Category::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($categorie);
        $em->flush();

        // On retourne une réponse clair au client (React, appli mobile, Insomnia, ..)
        return $this->json(
            [
                'message' => 'La catégorie ' . $categorie->getName() . ' a bien été créé'
            ],
            201 // 201 - Created https://developer.mozilla.org/fr/docs/Web/HTTP/Status/201
        );
    }

    /**
     * Met à jour une catégorie en fonction de son identifiant
     * 
     * PUT : Mise à jour totale. Si la ressource n'existe pas, on la crée
     * https://developer.mozilla.org/fr/docs/Web/HTTP/Methods/PUT
     * 
     * Si je veux mettre à jour la catégorie 200
     * - Si la catégorie 200 existe : on la met à jour
     * - Si la categorie 200 n'existe : on la créé
     * 
     * PATCH : mise à jour partielle
     * https://developer.mozilla.org/fr/docs/Web/HTTP/Methods/PATCH
     *
     * @Route("/{id}", name="update", methods={"PUT|PATCH"})
     * 
     * @return Response
     */
    public function update(Category $category, Request $request)
    {
        // On récupère les données au format JSON
        // issues de la requete (Insomnia, React, Js vanilla, ...)
        $jsonData = $request->getContent();

        // On transforme le json en tableau associatif (true)
        $arrayData = json_decode($jsonData, true);

        // On n'a plus qu'à manipuler ce tableau pour la sauvegarde de nos données
        $categoryName = $arrayData['name'];

        // Si la catégorie n'est pas vide
        if (!empty($categoryName)) {
            // Alors on la met à jour en BDD
            $category->setName($categoryName);

            // Puisqu'on fait une mise à jour, pas besoin de persist
            // car Doctrine est déjà au courant de l'existence de l'entité
            $this->em->flush();

            return $this->json([
                'message' => 'La catégorie ' . $category->getName() . ' a bien été mise à jour'
            ]);
        }

        // Code 400 : Bad request
        // Le client a mal effectué la requete permettant l'ajout d'une catégorie
        // https://developer.mozilla.org/fr/docs/Web/HTTP/Status/400
        return $this->json([
            'errors' => 'Merci de saisir un nom de catégorie'
        ], 400);
    }

    /**
     * Suppression d'une catégorie en BDD
     *
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * 
     * @return Response
     */
    public function delete(Category $category)
    {
        $this->em->remove($category);
        $this->em->flush();

        // return $this->json([
        //     'message' => 'Suppression de la catégorie ' . $category->getName()
        // ], 204);

        // Code 204 : https://developer.mozilla.org/fr/docs/Web/HTTP/Status/204
        return $this->json('', 204);
    }
}
