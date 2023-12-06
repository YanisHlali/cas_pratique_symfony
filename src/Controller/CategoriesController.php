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
            $this->categoriesRepository->create($categories);

            return $this->redirectToRoute('app_categories');
        }

        return $this->render('categories/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/categories', name: 'app_categories')]
    public function index(): Response
    {
        $categories = $this->categoriesRepository->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
        ]); 
    }

    #[Route('/category/{id}', name: 'app_categories_show')]
    public function show(int $id): Response
    {
        $category = $this->categoriesRepository->find($id);

        if (!$category) {
            $message = 'Cette catégorie n\'existe pas.';
        } else {
            $message = '';
        }

        return $this->render('categories/show.html.twig', [
            'category' => $category,
            'message' => $message,
        ]);
    }
 

    #[Route('/categories/{id}/edit', name: 'app_categories_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id): Response
    {
        $categories = $this->categoriesRepository->findOne($id);

        $form = $this->createForm(CategoriesType::class, $categories);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesRepository->update($categories);

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
        $category = $this->categoriesRepository->findOne($id);

        $this->categoriesRepository->delete($category);

        return $this->redirectToRoute('app_categories');
    }
}
