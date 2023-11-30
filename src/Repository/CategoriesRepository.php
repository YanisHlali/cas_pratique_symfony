<?php

namespace App\Repository;

use App\Entity\Categories;
use App\Entity\Books;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Categories>
 *
 * @method Categories|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categories|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categories[]    findAll()
 * @method Categories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,private LoggerInterface $logger)
    {
        parent::__construct($registry, Categories::class);
    }

    public function create(Categories $categories): void
    {
        $this->_em->persist($categories);
        $this->_em->flush();
    }

    public function findAll(): array
    {
        return $this->findBy([], ['name' => 'ASC']);
    }

    public function findOne(int $id): ?Categories
    {
        return $this->find($id);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
     {
          return parent::findBy($criteria, ['name' => 'ASC'], $limit, $offset);
     }

     public function findCategoryByBookId(int $bookId): array
     {
         $queryBuilder = $this->createQueryBuilder('c')
             ->leftJoin('c.books', 'b')
             ->addSelect('b')
             ->where('b.id = :id')
             ->setParameter('id', $bookId)
             ->getQuery();
 
         return $queryBuilder->getResult();
     }
    public function update(Categories $categories): void
    {
        $this->_em->flush();
    }

    public function delete(Categories $categories): void
    {
        $this->_em->remove($categories);
        $this->_em->flush();
    }

//    /**
//     * @return Categories[] Returns an array of Categories objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Categories
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
