<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Catalogue\Article;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur\Utilisateur;


class WishlistController extends AbstractController
{
    #[Route('/wishlist/add/{id}', name: 'wishlist_add', methods: ['POST'])]
    public function add(Article $article, EntityManagerInterface $entityManager): Response
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();
        if (!$user) {
            // Rediriger l'utilisateur vers la page de connexion ou donner une erreur
            return $this->redirectToRoute('app_login');
        }

        $wishlist = $user->getWishlist();

        if (!$wishlist) {
            // Créer la wishlist si elle n'existe pas
            $wishlist = new Wishlist();
            $wishlist->setUtilisateur($user);
            $user->setWishlist($wishlist);
            $entityManager->persist($wishlist);
        }

        if (!$wishlist->getArticles()->contains($article)) {
            $wishlist->addArticle($article);
            $entityManager->flush();

            return $this->json(['status' => 'added', 'message' => 'Article added to wishlist']);
        }

        return $this->json(['status' => 'exists', 'message' => 'Article already in wishlist']);
    }

    #[Route('/wishlist/remove/{id}', name: 'wishlist_remove', methods: ['POST'])]
    public function remove(Article $article, EntityManagerInterface $entityManager): Response
    {
        /** @var Utilisateur $user */
        $user = $this->getUser();
        $wishlist = $user->getWishlist();

        if ($wishlist->getArticles()->contains($article)) {
            $wishlist->removeArticle($article);
            $entityManager->flush();

            return $this->json(['status' => 'removed', 'message' => 'Article removed from wishlist']);
        }

        return $this->json(['status' => 'not_exists', 'message' => 'Article not in wishlist']);
    }


}
