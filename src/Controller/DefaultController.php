<?php

namespace App\Controller;

use App\Entity\Catalogue\Livre;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use App\DTO\SearchDto;
use App\Repository\LivreRepository;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(LivreRepository $livreRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $searchDto ??= new SearchDto();

        $categories = $livreRepository->getCategories(5);
        $featuredProducts = $livreRepository->getFeaturedProducts(5);

        $users = $utilisateurRepository->findAll();

        return $this->render('default/index.html.twig', [
            'searchDto' => $searchDto,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'users' => $users
        ]);
    }
}