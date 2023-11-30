<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoriesRepository;
use App\Entity\Categories;
use App\Form\CategoriesType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class CategoriesController extends AbstractController
{

    public function __construct(
        private CategoriesRepository $categoriesRepository,
        private EntityManagerInterface $em,
    ) {}

    #[Route('/categories/create', name: 'app_categories_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $categories = new Categories();
        $form = $this->createForm(CategoriesType::class, $categories);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde l'auteur
            $this->categoriesRepository->create($categories);

            // Redirige vers la liste des auteurs
            return $this->redirectToRoute('app_categories');
        }

        return $this->render('categories/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/categories', name: 'app_categories')]
    public function index(): Response
    {
        // Affiche tous les auteurs
        $categories = $this->categoriesRepository->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categories/{id}', name: 'app_categories_show')]
    public function show(int $id): Response
    {
        // Affiche un auteur
        $categories = $this->categoriesRepository->findOne($id);

        return $this->render('categories/show.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categories/{id}/edit', name: 'app_categories_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id): Response
    {
        // Récupère l'auteur
        $categories = $this->categoriesRepository->findOne($id);

        // Crée le formulaire
        $form = $this->createForm(CategoriesType::class, $categories);

        // Gère la requête
        $form->handleRequest($request);

        // Si le formulaire est envoyé et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde l'auteur
            $this->categoriesRepository->update($categories);

            // Redirige vers la liste des auteurs
            return $this->redirectToRoute('app_categories');
        }

        return $this->render('categories/edit.html.twig', [
            'form' => $form->createView(),
            'category' => $categories
        ]);
    }

    #[Route('/categories/{id}/delete', name: 'app_categories_delete', methods: ['GET', 'POST'])]
    public function delete(int $id): Response
    {
        // Récupère l'auteur
        $category = $this->categoriesRepository->findOne($id);

        // Supprime l'auteur
        $this->categoriesRepository->delete($category);

        // Redirige vers la liste des auteurs
        return $this->redirectToRoute('app_categories');
    }
}
