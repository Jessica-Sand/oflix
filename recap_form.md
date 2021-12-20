# Symfony Form

## Création du form Type

1. On crée le FormType ==> CharacterType (src/Form/Type/CharacterType.php)
2. On crée le triplet : Controleur - Methode - vue

## Affichage du formulaire

3. Dans le controleur

- Instancie l'entité Character 
- On lie l'entité au formulare : $form = $this->createForm
- On retourne une version "twig" du formulaire grâce à la méthode 
  $form->createView()

## Traitement du formulaire 

4. On récupère les données du formulaire en les injectant dans l'entité
   
- $form->handleRequest($request);

5. On vérifie qu'on est dans le cas d'une soumission de formulaire 
   + Vérification des données récupérée

6. Validation

- Si les données sont valides : On sauvegarde (entity manager) + redirection
- Sinon : on affiche de nouveau le formulaire + Message d'erreur


## Validation

- Liste des Assert disponible :https://symfony.com/doc/current/validation.html#supported-constraints