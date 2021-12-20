# Symfony - Oflix

```
TVSHOW : title, synopsis, image, nbLikes, published at

SEASON : season number, published at
HAS, 0N TVSHOW, 11 SEASON

EPISODE : episode number, title
CONTAINS, 0N SEASON , 11 EPISODE


CHARACTER : firstname, lastname, gender, bio, age
PLAY, 0N TVSHOW, 1N CHARACTER

CATEGORY : name
LINKED, 0N TVSHOW, 0N CATEGORY
```

![MCD Oflix](https://github.com/O-clock-Sheherazade/symfony-e07-challenge-manytomany/blob/main/mcd_mld_oflix.PNG)

## Objectif

On aimerait dans cet Atelier réaliser un début de back-office 😊

Le front-Office devra (à terme) nous permettre principalement :

- D'afficher une liste d'éléments d'une entité (Ex : liste de toutes les séries)
- D'afficher Les détails d'une entité (Ex : toutes les infos sur la série Scrubs 😜)

Quant au Back-offfice, il nous aidera à gérer le `CRUD` (Create - Read - Update - Delete) de chaque entité :

- Lister (`list`)
- Ajouter (`add`)
- Afficher (`show`)
- Modifier (`edit`)
- Ou supprimer (`delete`) des entités (Série, Catégorie, ...).  

(Plus tard on réflechira à comment _S'authentifier pour accèder à la partie back-office_ : **À ne pas faire aujourd'hui !**)

## Avant de coder :wink:

Après avoir cloné votre repository, effectuez les actions suivantes :

- Installation des dépendances du projet : `composer install`
- Configuration de la base de données : `.env.local` (`DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/oflix?serverVersion=mariadb-10.3.29"`)
    - `mysql --version` pour avoir la bonne version de MySQL/Maria-DB
- Optionnel (`php bin/console doctrine:database:drop --force`) (Uniquement si vous avez déjà une BDD nommée `oflix`)
- Création d'une Base de données Vide : `php bin/console doctrine:database:create` (d:d:c)
- Création d'un fichier de migration : `php bin/console make:migration` (ma:mi)
- Application de la migration (création des tables en BDD) : `php bin/console doctrine:migrations:migrate` (d:mi:mi)
- Fixtures : `php bin/console doctrine:fixtures:load`
- Let's go : `php -S localhost:8080 -t public`
- :coffee: ou :tea:

## Enoncé de l'Atelier

Créez une "interface d'admin" pour les entités ci-dessous uniquement (On en rajouterez dans le `Bonus Formulaires`) :  
- `Category`
- `Character`

Pour cela : 
- Créez un nouveau dossier `src/Admin` et 2 nouveaux controleurs 
    - `src/Admin/CategoryController.php`
    - `src/Admin/CharacterController.php`.
- Mettez en place les méthodes permettant de _lister/Ajouter/Afficher/Modifier/Supprimer_ (sans `make:form` pour le moment)
    - [Fetching Objects from the Database](https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database)
    - [_Persisting Objects to the Database_](https://symfony.com/doc/current/doctrine.html#persisting-objects-to-the-database)
    - [Updating an Object](https://symfony.com/doc/current/doctrine.html#updating-an-object)
    - [Deleting an Object](https://symfony.com/doc/current/doctrine.html#deleting-an-object) 
    - [Formulaire Symfony](https://symfony.com/doc/current/forms.html)
- Les routes doivent être préfixées par `admin` (Voir [Route Groups and Prefixes](https://symfony.com/doc/current/routing.html#route-groups-and-prefixes))
    - On aura par ex. les routes `list, add, show, edit, delete` pour chaque entité.
- Mettre les [requirements](https://symfony.com/doc/current/routing.html#parameters-validation) sur les routes avec paramètres.
- Effectuer des *redirections* dans les actions où cela s'avère nécessaire.

- Dans la navigation, Ajoutez un lien _Admin_ vers le backoffice.

>>> Vous pouvez vous inspirer de ce qui est proposé dans le projet [Démo de Symfony](https://github.com/symfony/demo/blob/main/src/Controller/Admin/BlogController.php)

:warning: Attention ! N'incluez ni l'authentification, ni les voters (`@IsGranted("ROLE_ADMIN")`, `$this->denyAccessUnlessGranted`). Notions que l'on abordera plus tard (Réponse D :wink:)

### Bonus Formulaires sur l'entité Show

Comme aux étapes précédentes, on va créer 1 nouveau controleur pour l'entité `TvShow`
- `src/Admin/TvShowController.php`
- Mettez en place les méthodes permettant de _lister/Ajouter/Afficher/Modifier/Supprimer_
    - Utilisez la commande `make:form` : Celle-ci vous crée un formulaire complet, avec les relations entre entités si nécessaire. 

<details>

```bash
php bin/console make:form TvShow

 The name of Entity or fully qualified model class name that the new form will be bound to (empty for none):
 > TvShow
```
```php
// src/Form/TvShowType
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('title')
        ->add('synopsis')
        ->add('image')
        ->add('nbLikes')
        ->add('publishedAt')
        ->add('createdAt')
        ->add('updatedAt')
        ->add('characters')
        ->add('categories')
        ->add('save', SubmitType::class, [
                'label' => 'Valider',
        ]);
}
```
</details>

- Modifiez le formulaire selon vos besoins : types de champs (SubmitType, TextType, NumberType, ...) et leurs options éventuelles. ([Form Types Reference](https://symfony.com/doc/current/reference/forms/types.html))

<details>

```php
    // ...
        ->add('save', SubmitType::class, [
            'label' => 'Valider',
        ]);
}
```
</details>

- Affichez le formulaire d'ajout d'une nouvelle série depuis votre navigateur.

<details>

```php
/**
 * Ajout d'une nouvelle série
 * 
 * URL : /tvshow/add
 *
 * @Route("/add", name="add")
 * 
 * @return void
 */
public function add(Request $request)
{
    $tvShow = new TvShow();

    $form = $this->createForm(TvShowType::class, $tvShow);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        dd($tvShow);
    }

    return $this->render('tvshow/add.html.twig', [
        'form' => $form->createView()
    ]);
}
```

</details>

- Vous avez une erreur :boom: !
    - Pas de panique : Symfony essaie d'afficher le Form Type `characters` qui renvoie...un objet. Et PHP (le langage derrière Symfony :wink:) n'aime pas qu'on fasse un `echo` sur un objet.
    - Solution : Dans l'entité `Character`, ajoutez la méthode magique `__toString` que PHP appelle en renfort lorsque l'on tente de faire un `echo` sur un objet
    
    ```php
    // src/Entity/Character.php

    /**
     * Si l'on tente de faire un echo sur l'objet Character, PHP retournera la valeur du prénom
     */
    public function __toString()
    {
        return $this->firstname;
    }
    ```
    - Faites la même chose pour l'entité `Category`

- Remplissez le formulaire, sélectionnez des personnages/catégories et Cliquez sur le bouton de validation
- Vérifiez que vos données sont bien présentes en faisant un `dd`. Inspectez en particulier les propriétés `characters` et `categories` :tada:
- Faites persister vos données en BDD

```php
$em = $this->getDoctrine()->getManager();
$em->persist($tvShow);
$em->flush();

return $this->redirectToRoute('tvshow_list');
```
- Votre série apparait dans la liste (triée par title ascendant :wink:)
- Et voilà ! :tada: 

Savourez cette grande victoire :coffee:

Puis : 

- Appliquez des [contraintes de validation](https://symfony.com/doc/current/reference/constraints.html) sur les propriétés de l'entité (recommandé).
    - Vérifier les contraintes en soumettant un formulaire vide ([`Asset\NotBlank`](https://symfony.com/doc/current/reference/constraints/NotBlank.html)), en prenant soin de désactiver la validation HTML5 ([`novalidate`](https://symfony.com/doc/current/forms.html#client-side-html-validation)).
- [Le thème Bootstrap](https://symfony.com/doc/current/form/bootstrap5.html) est activé sur les formulaires, alors n'hésitez pas à rajouter des classes CSS de votre choix sur les form type ((Voir un exemple ici)[https://github.com/O-clock-Sheherazade/symfony-e06-challenge-oflix-charlesen/blob/master/oflix/src/Form/Type/CategoryType.php#L30])

### Bonus au choix (L'un ou l'autre ou les trois :wink:)

- Ajouter une image pour une serie ou un personnage, et Affichez-les dans les vues détaillées correspondates ([How to Upload Files
](https://symfony.com/doc/current/controller/upload_file.html)
- Ajouter des fixtures pour toutes les entités créées.

## Lecture
- [Symfony Security](https://symfony.com/doc/current/security.html)