<?php

namespace App\Repository;

use App\Entity\TvShow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TvShow|null find($id, $lockMode = null, $lockVersion = null)
 * @method TvShow|null findOneBy(array $criteria, array $orderBy = null)
 * @method TvShow[]    findAll()
 * @method TvShow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TvShowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TvShow::class);
    }

    /**
     * Méthode retournant toutes les séries triées par
     * ordre alphabétique 
     * 
     * Solution 1 : Query Builder
     *
     * @return void
     */
    public function findAllOrderByTitle()
    {
        // L'alias va faire référence à l'entité courante (TvShow)
        $qb = $this->createQueryBuilder('tv'); // SELECT * tv_show 

        // On veut trier le résultat par title ASC
        $qb->orderBy('tv.title', 'ASC'); // ORDER BY title

        // On crée la requete SQL
        $query = $qb->getQuery();

        // On execute et on retourne le résultat sous forme de tableau
        // d'objets de la classe TvShow
        return $query->getResult();
    }

    /**
     * Méthode retournant toutes les séries triées par
     * ordre alphabétique 
     * 
     * Solution 2 : DQL
     *
     * @return void
     */
    public function findAllOrderByTitleDQL()
    {
        // 1) On appelle le manager
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT tv
             FROM App\Entity\TvShow tv
             ORDER BY tv.title ASC'
        );

        // retourne le résultat sous forme de tableau d'objets de la classe TvShow
        return $query->getResult();
    }


    /**
     * Méthode retournant tous les détails d'une série :
     * - Infos de la série : title, description, ...
     * - Les saisons associées
     * - les catégories
     * - les personnages
     *
     * @param int $id
     * @return void
     */
    public function findWithDetails($id)
    {
        $qb = $this->createQueryBuilder('tv'); // SELECT tv_show.*

        // On cible la série demandée ($id)
        // SELECT tv_show.* WHERE id = $id
        $qb->where('tv.id = :id'); // Requete pour éviter toute injection sql
        $qb->setParameter(':id', $id);

        // Jointure sur les saisons
        // la méthode "join" (innerJoin) est beaucoup plus strict :
        // - Il faut non seulement avoir des séries, mais il faut aussi
        //  qu'il y ait des saisons associées à la série
        // - Si on a une série, mais pas de saison, la méthode retourne null

        // la méthode leftJoin (left join) :
        // - Si on a une série, et qu'on a pas de saisons associée, 
        // on retourne quand même les informations de la série

        // jointure pour récupérer les saisons de la série
        // tv.seasons ===> propriété de l'entité TvShow
        // seasons ===> Alias de la jointure (nom au choix)
        $qb->leftJoin('tv.seasons', 'seasons');

        // jointure pour récupérer les personnages de la série
        $qb->leftJoin('tv.characters', 'characters');

        // jointure pour récupérer les catégories de la série
        $qb->leftJoin('tv.categories', 'categories');

        // jointure pour récupérer les épisode de la série
        $qb->leftJoin('seasons.episodes', 'episodes');

        // On demande de récupérer les informations des autres tables
        $qb->addSelect('seasons, characters, categories, episodes');

        // On crée la requete SQL
        $query = $qb->getQuery();

        // On execute et on retourne le résultat sous forme de tableau
        // d'objets de la classe TvShow
        // getOneOrNullResult : 
        // - retourne null si aucun résultat
        // - retourne 1 objet de la clase TvShow
        // return $query->getOneOrNullResult();
        return $query->getOneOrNullResult();
    }

    /**
     * Méthode retournant tous les détails d'une série :
     * - Infos de la série : title, description, ...
     * - Les saisons associées
     * - les catégories
     * - les personnages
     *
     * @param int $id
     * @return void
     */
    public function findWithDetailsDQL($id): ?TvShow
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT tv, seasons, categories, characters
            FROM App\Entity\TvShow tv
            LEFT JOIN tv.seasons seasons
            LEFT JOIN tv.categories categories
            LEFT JOIN tv.characters characters
            WHERE tv.id = :id
            ORDER BY seasons.seasonNumber DESC
            '
        )->setParameter('id', $id);

        // On execute et on retourne le résultat sous forme de tableau
        // d'objets de la classe TvShow
        return $query->getOneOrNullResult();
    }

    /***
     * Méthode permettant de retourner une série en fonction de son title
     * 
     * Solution 1 : DQL
     */
    public function findSearchByTitleDQL($title)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT tv
            FROM App\Entity\TvShow tv
            WHERE tv.title LIKE :title
            '
        )->setParameter(':title', "%$title%");

        return $query->getResult();
    }

    /***
     * Méthode permettant de retourner une série en fonction de son title
     * 
     * Solution 2 : Query Builder
     */
    public function findSearchByTitle($title)
    {
        // On instancie le querybuilder
        $qb = $this->createQueryBuilder('tv'); // SELECT * FROM tv_show

        $qb->where('tv.title LIKE :title'); // WHERE title LIKE %title%

        $qb->setParameter(':title', "%$title%");

        $query = $qb->getQuery();

        return $query->getResult();
    }

    /**
     * Méthode retournant tous les détails d'une série avec slug
     *
     * @param string $slug
     */
    public function findByOne($slug): ?TvShow
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT tv, seasons, categories, characters
            FROM App\Entity\TvShow tv
            LEFT JOIN tv.seasons seasons
            LEFT JOIN tv.categories categories
            LEFT JOIN tv.characters characters
            WHERE tv.slug = :slug
            ORDER BY seasons.seasonNumber DESC
            '
        )->setParameter('slug', $slug);

        // On execute et on retourne le résultat sous forme de tableau
        // d'objets de la classe TvShow
        return $query->getOneOrNullResult();
    }


    // /**
    //  * @return TvShow[] Returns an array of TvShow objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TvShow
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
