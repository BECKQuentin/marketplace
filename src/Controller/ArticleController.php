<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ArticleCreateFormType;
use App\Form\ArticleSearchFormType;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    #[Route('articles', name: 'app_articles')]
    public function allArticles(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'app_article')]
    public function showArticle(EntityManagerInterface $entityManager, int $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            return $this->redirectToRoute('app_articles');
        } else {
            return $this->render('article/index.html.twig', [
                'articles' => [$article],
            ]);
        }
    }

    #[Route('/article_create', name: 'app_add_article')]
    public function addArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleCreateFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article', ['id' => $article->getId()]);
        }

        return $this->renderForm('article/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/article_search', name: 'app_search_article')]
    public function searchArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleSearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$form->getData();

            // Recuperer les infos du formulaire
            // $name = $form->get('name')->getData();
            // $description_too = $form->get('description_too')->getData();
            // $price_min = $form->get('price_min')->getData();
            // $price_max = $form->get('price_max')->getData();

            // Faire la requête qui va chercher les articles qui correspondent aux filtres du formulaire
            $articles = $entityManager->getRepository(Article::class)->findBySearchForm($form);

            // Render la vue
            return $this->render('article/index.html.twig', [
                'articles' => $articles,
            ]);
        }

        return $this->renderForm('article/create.html.twig', [
            'form' => $form,
        ]);
    }
}
