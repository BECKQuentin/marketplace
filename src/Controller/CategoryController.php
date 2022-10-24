<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use App\Form\CategoryCreateFormType;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    #[Route('admin/categories', name: 'app_categories')]
    // #[Security("is_granted('ROLE_ADMIN')", statusCode: 403, message: "Resource not found.")]
    public function allCategories(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/category/{id}', name: 'app_category')]
    public function showCategory(EntityManagerInterface $entityManager, int $id): Response
    {
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            return $this->redirectToRoute('app_categories');
        } else {
            return $this->render('category/index.html.twig', [
                'categories' => [$category],
            ]);
        }
    }

    #[Route('/admin/category_create', name: 'app_add_category')]
    public function addCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryCreateFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();

            $entityManager->persist($category);
            $entityManager->flush();

            //return $this->redirectToRoute('app_categories');

            // TODO redirect to route app_category with ID as param
            return $this->redirectToRoute('app_category', ['id' => $category->getId()]);
        }

        return $this->renderForm('category/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/category_edit/{id}', name: 'app_edit_category')]
    public function editCategory(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $category = $entityManager->getRepository(Category::class)->find($id);

        // If no category, redirect to list of categories
        if (!$category) {
            return $this->redirectToRoute('app_categories');
        }

        $form = $this->createForm(CategoryCreateFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_categories');
        }

        return $this->renderForm('category/create.html.twig', [
            'form' => $form,
        ]);
    }
}
