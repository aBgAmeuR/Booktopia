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
use App\Repository\LivreRepository;

use Doctrine\ORM\EntityManagerInterface;
use App\DTO\SearchDto;

class CartController extends AbstractController


{

    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    private LivreRepository $livreRepository;





    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, LivreRepository $livreRepository,)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->livreRepository = $livreRepository;
    }

    /**
     * @Route("/cart", name="cart_index")
     */
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

    /**
     * @Route("/cart/add/{id}", name="add_to_cart", methods={"POST"})
     */
    public function add($id, Request $request)
    {
        $livre = $this->livreRepository->find($id);
        if (!$livre) {
            throw $this->createNotFoundException('Le livre demandé n\'existe pas');
        }
        $panier = $this->getPanierFromSession($request);
        $panier->ajouterLigne($livre);
        $searchDto ??= new SearchDto();

        return $this->redirectToRoute('cart_index');
    }
}
