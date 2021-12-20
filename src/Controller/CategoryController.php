<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * Méthode permettant d'afficher le formulaire d'ajout d'une catégorie
     * Mais aussi la sauvegarde de la catégorie en BDD
     * 
     * @deprecated Cette méthode disparaitra au profit de src/Admin/Controller::add (Cf. Atelier Backoffice)
     * 
     * @Route("/add", name="add")
     */
    public function add(Request $request): Response
    {
        $category = new Category();

        // On va lier le formulaire à l'entité Category
        $form = $this->createForm(CategoryType::class, $category);

        // La méthode requete va permettre l'injection des données
        // issues du formulaire dans l'objet $category
        $form->handleRequest($request);

        // Si $form->isSubmitted() renvoie true, 
        // alors on sauvegarde le form
        if ($form->isSubmitted() && $form->isValid()) {
            // Après soumission du formulaire, on va pouvoir
            // sauvegarder nos données
            // L'objet catégorie contient les données transmises lors
            // du clic sur le bouton "Valider"
            $em = $this->getDoctrine()->getManager();

            // On sauvegarde la catégorie en BDD
            $em->persist($category);
            $em->flush();

            // On redirige vers la liste des catégories
            return $this->redirectToRoute('category_list');
        }

        // Sinon On affiche le formulaire d'ajout d'une catégorie
        return $this->render('category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
