<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findAllPostedAfter($datePost){
        $connexion = $this->getEntityManager()->getConnection();
        $sql= '
            SELECT * FROM article 
            WHERE date_publi > :datePost 
            ORDER BY date_publi DESC
            ';
        $select = $connexion->prepare($sql);
        $select -> bindValue(':datePost', $datePost);
        $select->execute();
        return $select->fetchAll();
    }
    //même chose mais en objet
    public function findAllPostedAfterSansLesMains($datePost){
        $queryBuilder = $this->createQueryBuilder('a')
            ->andWhere('a.date_publi > :datepost')
            ->setParameter('datePost', $datePost)
            ->orderBy('a.date_publi', 'DESC')
            ->getQuery();
        return $queryBuilder->execute();
    }
}
