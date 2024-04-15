<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Catalogue\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\DTO\SearchDto;
use App\Entity\Utilisateur\Utilisateur;

class WishlistController extends AbstractController
{
    #[Route('/wishlist/toggle', name: 'app_wishlist_toggle', methods: ['POST'])]
    public function toggle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $articleId = $request->get('articleId');
        $isInWishlist = $request->get('isInWishlist');

        $article = $entityManager->getRepository(Article::class)->find($articleId);
        $user = $this->getUser();

        if ($isInWishlist) {
            $user->getWishlist()->removeArticle($article);
        } else {
            $user->getWishlist()->addArticle($article);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_product', ['id' => $article->getId()]);
    }

    #[Route('/wishlist/delete', name: 'app_wishlist_delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        $articleId = $request->get('articleId');
        $article = $entityManager->getRepository(Article::class)->find($articleId);
        $user = $this->getUser();

        $user->getWishlist()->removeArticle($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_wishlist');
    }

    #[Route('/favorites', name: 'app_wishlist')]
    public function index(): Response
    {
        $searchDto ??= new SearchDto();
        /** @var Utilisateur $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $articles = $user->getWishlist()->getArticles();
        return $this->render('wishlist/index.html.twig', [
            'articles' => $articles,
            'searchDto' => $searchDto,
        ]);
    }


}
