<?php

namespace App\Repository;

use App\Entity\Books;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
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
            $categoryNames = $this->categoriesRepository->findCategoryByBookId($book->getId());
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
    
        $categoryNames = $this->categoriesRepository->findCategoryByBookId($book->getId());
    
        $bookData = [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'authorName' => $book->getAuthor()->getName(),
            'isbn' => $book->getIsbn(),
            'date' => $book->getDate() ? $book->getDate()->format('Y-m-d') : null,
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
}
