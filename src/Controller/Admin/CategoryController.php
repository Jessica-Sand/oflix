<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends AbstractController
{
     /**
     * 
     * URL : /admin/category/list , nom de la route : admin_category_list
     * 
     * @Route("/list", name="list")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * 
     * @Route("/add", name="add")
     * 
     * @return void
     */
    public function add(Request $request): Response
    {
        // 1) On crée une entité vide que l'on va lier au formulaire
        $category = new Category();

        // 2) On lie le formulaire à l'entité Category
        $form = $this->createForm(CategoryType::class, $category);

        // 4) La méthode requete va permettre l'injection des données
        // issues du formulaire dans l'objet $category
        $form->handleRequest($request);

        // 5) Si $form->isSubmitted() renvoie true, 
        // alors on sauvegarde le form
        if ($form->isSubmitted() && $form->isValid()) {
            // 6) Après soumission du formulaire, on va pouvoir
            // sauvegarder nos données
            // L'objet catégorie contient les données transmises lors
            // du clic sur le bouton "Valider"
            $em = $this->getDoctrine()->getManager();

            // On sauvegarde la catégorie en BDD
            $em->persist($category);
            $em->flush();

            // Message flash
            // $_SESSION['tototiti'] = 'La categorie ...';
            $this->addFlash('info', 'La catégorie ' . $category->getName() . ' a bien été créée');

            // 7) On redirige vers la liste des catégories
            return $this->redirectToRoute('admin_category_list');
        }

        // 3) Sinon On affiche le formulaire d'ajout d'une catégorie
        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/{id}", name="show", requirements={"index" = "\d+"})
     * @return void
     */
    public function show(int $id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);  

        // dd($category);

        return $this->render('admin/category/show.html.twig', [
            'category' => $category
        ]);
    }

    /**
     *
     * @Route("/edit/{id}", name="edit", requirements={"index" = "\d+"}, methods={"GET|POST"})
     * @return void
     */
    public function edit(Category $category, Request $request): Response
    {
        /// $category contient les données avant modification
        $form = $this->createForm(CategoryType::class, $category);

        // On capture les données du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si le formulaire a été submit, alors on sauvegarde les données
            // $category contient les données après modification
            $em = $this->getDoctrine()->getManager();
            // $em->persist($category) // Pas nécessaire, car déjà connu de Doctrine
            $em->flush();

            // Message flash
            $this->addFlash('success', 'La catégorie ' . $category->getName() . ' a bien été enregistrée');

            // On redirige ensuite vers la page de listing des catégories
            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin/category/edit.html.twig', [
            'formView' => $form->createView()
        ]);
    }

    /**
     * Methode permettant la suppression d'une catégorie
     *
     * @Route("/{id}/delete", name="delete")
     * @return void
     */
    public function delete(int $id, CategoryRepository $categoryRepository, Request $request)
    {
        $submittedToken = $request->get('token');
        
        if ($this->isCsrfTokenValid('delete-category', $submittedToken)) {
            // L'action de delete a bien été initialisé depuis le formulaire
            // du site            
            $categoryToDelete = $categoryRepository->find($id);
            $categoryName = $categoryToDelete->getName();
            if ($categoryToDelete === null) {
                // Je génère une 404
                throw $this->createNotFoundException('La ressource demandée n\'existe pas');
            }

            // On supprime l'entité en BDD
            $em = $this->getDoctrine()->getManager();
            $em->remove($categoryToDelete);
            $em->flush();

            $this->addFlash('success', 'La categorie ' . $categoryName . ' a bien été supprimée');

            return $this->redirectToRoute('admin_category_list');
        } else {
            return new Response('Action interdite', 403);
        }
    }
}