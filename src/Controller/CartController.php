<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Psr\Log\LoggerInterface;

use App\Entity\Catalogue\Article;
use App\Entity\Panier\Panier;
use App\Entity\Panier\LignePanier;

use Doctrine\ORM\EntityManagerInterface;
use App\DTO\SearchDto;

class CartController extends AbstractController


{

    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    private Panier $panier;
    private $panierRepository;


    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    #[Route('/cart', name: 'cart_index')]
    public function index(Request $request)
    {
        $searchDto ??= new SearchDto();
        $panier = $this->getPanierFromSession($request);

        return $this->render('panier.html.twig', [
            'searchDto' => $searchDto,
            'panier' => $panier

        ]);
    }

    private function getPanierFromSession(Request $request)
    {
        $session = $request->getSession();
        // Tentez de récupérer le panier de la session, sinon créez-en un nouveau
        $panier = $session->get('panier', new Panier());

        // (Optionnel) Sauvegarder le nouveau panier dans la session si nécessaire
        $session->set('panier', $panier);

        return $panier;
    }
}
