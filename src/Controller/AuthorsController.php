<?php

namespace App\Controller;

use App\Entity\Authors;
use App\Form\AuthorsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuthorsRepository;
use Symfony\Component\HttpFoundation\Request;

class AuthorsController extends AbstractController
{
    public function __construct(
        private AuthorsRepository $authorsRepository,
        private EntityManagerInterface $em,
    ) {}

    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(): Response
    {
        // Affiche tous les auteurs
        $authors = $this->authorsRepository->findAll();

        return $this->render('authors/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/authors/create', name: 'app_authors_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $author = new Authors();
        $form = $this->createForm(AuthorsType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde l'auteur
            $this->authorsRepository->create($author);

            // Redirige vers la liste des auteurs
            return $this->redirectToRoute('app_authors');
        }

        return $this->render('authors/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/authors', name: 'app_authors')]
    public function index(): Response
    {
        // Affiche tous les auteurs
        $authors = $this->authorsRepository->findAll();

        return $this->render('authors/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/authors/{id}', name: 'app_authors_show')]
    public function show(int $id): Response
    {
        $author = $this->authorsRepository->findOne($id);

        return $this->render('authors/show.html.twig', [
            'author' => $author,
            'books' => $author->getBooks()->toArray(),
        ]);
    }

    #[Route('/authors/{id}/edit', name: 'app_authors_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id): Response
    {
        // Récupère l'auteur
        $author = $this->authorsRepository->findOne($id);

        // Crée le formulaire
        $form = $this->createForm(AuthorsType::class, $author);

        // Traite le formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde l'auteur
            $this->authorsRepository->update($author);

            // Redirige vers la liste des auteurs
            return $this->redirectToRoute('app_authors');
        }

        return $this->render('authors/edit.html.twig', [
            'form' => $form->createView(),
            'author' => $author,
        ]);
    }

    #[Route('/authors/{id}/delete', name: 'app_authors_delete', methods: ['GET'])]
    public function delete(int $id): Response
    {
        // Récupère l'auteur
        $author = $this->authorsRepository->findOne($id);

        // Supprime l'auteur
        $this->authorsRepository->delete($author);

        // Redirige vers la liste des auteurs
        return $this->redirectToRoute('app_authors');
    }
}
