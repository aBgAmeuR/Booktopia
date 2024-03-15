<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\DTO\SearchDto;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use App\Repository\LivreRepository;

class ProductController extends AbstractController
{

    #[Route('/product/{id}', name: 'app_product')]
    public function index(#[MapQueryString()] ?SearchDto $searchDto = null, $id, LivreRepository $livreRepository): Response
    {
        $searchDto ??= new SearchDto();

        $livre = $livreRepository->getById($id);

        if (!$livre) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

        $livresLike = $livreRepository->getLikeArticles($livre);
        
        return $this->render('product/index.html.twig', [
            'livre' => $livre,
            'searchDto' => $searchDto,
            'livresLike' => $livresLike
        ]);
    }
}
