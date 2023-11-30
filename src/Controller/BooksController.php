<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BooksRepository;
use App\Entity\Books;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\BooksType;

class BooksController extends AbstractController
{

    public function __construct(
        private BooksRepository $booksRepository,
        private EntityManagerInterface $em,
    ) {}

    #[Route('/books/create', name: 'app_books_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $books = new Books();
        $form = $this->createForm(BooksType::class, $books);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde l'auteur
            $this->booksRepository->create($books);

            // Redirige vers la liste des auteurs
            return $this->redirectToRoute('app_categories');
        }

        return $this->render('books/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/books', name: 'app_books')]
    public function index(): Response
    {
        $books = $this->booksRepository->findAll();

        # Vérifier si l'objet est vide
        if (!$books) {
            throw $this->createNotFoundException(
                'No books found in books\' table.'
            );
        }

        return $this->render('books/index.html.twig', [
            'data' => $books,
        ]);
    }

    #[Route('/books/{id}', name: 'app_books_show')]
    public function show(int $id): Response
    {
        // Affiche un auteur
        $books = $this->booksRepository->findOne($id);

        return $this->render('books/show.html.twig', [
            'book' => $books,
        ]);
    }

    #[Route('/books/{id}/edit', name: 'app_books_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id): Response
    {
        $books = $this->booksRepository->findOneById($id);

        $form = $this->createForm(BooksType::class, $books);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde l'auteur

            $this->booksRepository->update($books);

            // Redirige vers la liste des auteurs
            return $this->redirectToRoute('app_books');
        }

        return $this->render('books/edit.html.twig', [
            'form' => $form->createView(),
            'book' => $books,
        ]);
    }

    #[Route('/books/{id}/delete', name: 'app_books_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, int $id): Response
    {
        $books = $this->booksRepository->findOneBookWithAuthorAndCategories($id);

        $form = $this->createForm(BooksType::class, $books);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde l'auteur

            $this->booksRepository->delete($books);

            // Redirige vers la liste des auteurs
            return $this->redirectToRoute('app_books');
        }

        return $this->render('books/delete.html.twig', [
            'form' => $form->createView(),
            'book' => $books,
        ]);
    }


}
