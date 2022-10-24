<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySearchForm($form)
    {
        $name = $form->get('name')->getData();
        $description_too = $form->get('description_too')->getData();
        $price_min = $form->get('price_min')->getData();
        $price_max = $form->get('price_max')->getData();
        $category = $form->get('category')->getData();
        // $city = $form->get('city')->getData();

        /*
        SELECT * FROM article
        WHERE (name LIKE :name
        OR description LIKE :name)
        AND price >= :price_min
        AND price <= :price_max
        AND category = :category
        ORDER BY name ASC
        LIMIT :start_pos, :nb_per_page
        */

        $query = $this->createQueryBuilder('a');

        $bool_where = false;
        if (!empty($name)) {
            $query->andWhere('a.name LIKE :name');
            if ($description_too) {
                $query->andWhere('a.description LIKE :name');
            }
            $query->setParameter('name', '%'.$name.'%');
        }

        if (!empty($price_min)) {
            $query->andWhere('a.price >= :price_min')
            ->setParameter('price_min', $price_min)
            ;
        }
        if (!empty($price_max)) {
            $query->andWhere('a.price <= :price_max')
            ->setParameter('price_max', $price_max)
            ;
        }
        if (!empty($category)) {
            $query->andWhere('a.category = :category')
            ->setParameter('category', $category)
            ;
        }

        $query->orderBy('a.name', 'ASC');
        
        dd($query->getQuery());

        return $query->getQuery()->getResult();
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
