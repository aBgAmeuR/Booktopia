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

class CartController extends AbstractController


{

    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    private Panier $panier;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    #[Route('/cart', name: 'cart_index')]
    public function index()
    {
        return $this->render('panier.html.twig');
    }
}
