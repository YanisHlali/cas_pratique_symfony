<?php

namespace App\Repository;

use App\Entity\Books;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Authors;
use App\Entity\Categories;
use App\Repository\CategoriesRepository;

/**
 * @extends ServiceEntityRepository<Books>
 *
 * @method Books|null find($id, $lockMode = null, $lockVersion = null)
 * @method Books|null findOneBy(array $criteria, array $orderBy = null)
 * @method Books[]    findAll()
 * @method Books[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BooksRepository extends ServiceEntityRepository
{
    public function __construct(
    ManagerRegistry $registry,
    private CategoriesRepository $categoriesRepository,)
    {
        parent::__construct($registry, Books::class);
    }

    public function create(Books $book): void
    {
        $this->_em->persist($book);
        $this->_em->flush();
    }

    // src/Repository/BooksRepository.php

    public function findBookWithCategories($bookId)
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.categories', 'c')
            ->addSelect('c')
            ->where('b.id = :id')
            ->setParameter('id', $bookId)
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function findAll(): array
    {
        $books = $this->findBy([], ['title' => 'ASC']);
        $bookData = [];
    
        foreach ($books as $book) {
            // Utiliser CategoriesRepository pour trouver les noms des catégories
            $categoryNames = $this->categoriesRepository->findCategoryByBookId($book->getId());
            // Créer un objet temporaire pour chaque livre
            $tempBook = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'authorName' => $book->getAuthor()->getName(),
                'isbn' => $book->getIsbn(),
                'date' => $book->getDate(),
                'categoryNames' => $categoryNames,
            ];
    
            $bookData[] = $tempBook;
        }
    
        return $bookData;
    }

    public function findOne(int $id): ?array
    {
        $book = $this->find($id);
    
        if (!$book) {
            return null;
        }
    
        // Utiliser CategoriesRepository pour trouver les noms des catégories
        $categoryNames = $this->categoriesRepository->findCategoryByBookId($book->getId());
    
        // Créer un tableau pour le livre
        $bookData = [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'authorName' => $book->getAuthor()->getName(),
            'isbn' => $book->getIsbn(),
            'date' => $book->getDate() ? $book->getDate()->format('Y-m-d') : null, // Formattez la date ici
            'categoryNames' => $categoryNames,
        ];
    
        return $bookData;
    }

    public function findOneById(int $id) :?Books
    {
        return $this->find($id);
    }

    public function update(Books $book): void
    {
        $this->_em->flush();
    }
    
    
    

//    /**
//     * @return Books[] Returns an array of Books objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Books
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
