<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(EntityManagerInterface $em): Response
    {
        $articles = $em->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/articles/new', name: 'app_article_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTimeImmutable());
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/articles/{id}', name: 'app_article_show')]
    public function show(int $id, EntityManagerInterface $em): Response
    {
        $article = $em->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article introuvable 😢');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

}
