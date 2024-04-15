<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\DTO\SearchDto;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use App\Repository\LivreRepository;
use App\Repository\WishlistRepository;

class ProductController extends AbstractController
{

    #[Route('/product/{id}', name: 'app_product')]
    public function index(#[MapQueryString()] ?SearchDto $searchDto = null, $id, LivreRepository $livreRepository, WishlistRepository $wishlistRepository): Response
    {
        $searchDto ??= new SearchDto();

        $livre = $livreRepository->getById($id);

        if (!$livre) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

        $livresLike = $livreRepository->getLikeArticles($livre);

        $is_in_wishlist = false;
        if ($this->getUser()) $is_in_wishlist = $wishlistRepository->isArticleInWishlist($livre->getId(), $this->getUser()->getId());

        return $this->render('product/index.html.twig', [
            'livre' => $livre,
            'searchDto' => $searchDto,
            'livresLike' => $livresLike,
            'is_in_wishlist' => $is_in_wishlist
        ]);
    }
}
