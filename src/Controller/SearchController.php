<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\DTO\SearchDto;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(#[MapQueryString()] ?SearchDto $searchDto = null): Response
    {
        $searchDto ??= new SearchDto();

        return $this->render('search/index.html.twig', [
            'searchDto' => $searchDto,
        ]);
    }
}
