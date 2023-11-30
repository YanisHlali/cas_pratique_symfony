<?php

namespace App\Repository;

use App\Entity\Authors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Authors>
 *
 * @method Authors|null find($id, $lockMode = null, $lockVersion = null)
 * @method Authors|null findOneBy(array $criteria, array $orderBy = null)
 * @method Authors[]    findAll()
 * @method Authors[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Authors::class);
    }

    public function create(Authors $author): void
    {
        $this->_em->persist($author);
        $this->_em->flush();
    }

    public function findAll(): array
    {
        return $this->findBy([], ['name' => 'ASC']);
    }

    public function findOne(int $id): ?Authors
    {
        return $this->find($id);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
     {
          return parent::findBy($criteria, ['name' => 'ASC'], $limit, $offset);
     }

    public function update(Authors $author): void
    {
        $this->_em->flush();
    }

    public function delete(Authors $author): void
    {
        $this->_em->remove($author);
        $this->_em->flush();
    }

//    /**
//     * @return Authors[] Returns an array of Authors objects
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

//    public function findOneBySomeField($value): ?Authors
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
