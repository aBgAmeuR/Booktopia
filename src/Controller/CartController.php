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
use App\Entity\Utilisateur\Commande;

use Doctrine\ORM\EntityManagerInterface;
use App\DTO\SearchDto;

class CartController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    private LivreRepository $livreRepository;
    private Panier $panier;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, LivreRepository $livreRepository, )
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

        $user = $this->getUser();
        $commandes = null;
        if ($user) {
            $commandes = $user->getCommandes();
        }

        return $this->render('panier.html.twig', [
            'searchDto' => $searchDto,
            'panier' => $panier,
            'commandes' => $commandes,
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
        $quantite = $request->request->get('quantity');
        if (!$livre) {
            throw $this->createNotFoundException('Le livre demandé n\'existe pas');
        }
        $panier = $this->getPanierFromSession($request);
        $panier->ajouterLigne($livre, $quantite);
        $searchDto ??= new SearchDto();

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/supprimerLigne/{id}', name: 'supprimerLigne')]
    public function supprimerLigneAction(Request $request, string $id): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $panier = $session->get("panier", new Panier());
        $panier->supprimerLigne($id);
        $session->set("panier", $panier);
        return $this->redirectToRoute('cart_index');

        if (count($panier->getLignesPanier()) === 0) {
            return $this->render('panier.vide.html.twig');
        } else {
            return $this->render('panier.html.twig', [
                'panier' => $panier,
            ]);
        }
    }

    #[Route('/recalculerPanier', name: 'recalculerPanier', methods: ["GET", "POST"])]
    public function recalculerPanierAction(Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted())
            $session->start();
        if ($session->has("panier"))
            $this->panier = $session->get("panier");
        else
            $this->panier = new Panier();
        $it = $this->panier->getLignesPanier()->getIterator();
        while ($it->valid()) {
            $ligne = $it->current();
            $article = $ligne->getArticle();
            // cart[1141555897821]["qty"]=4   https://symfony.com/doc/6.4/components/http_foundation.html
            $ligne->setQuantite($request->request->all("cart")[$article->getId()]["qty"]);
            $ligne->recalculer();
            $it->next();
        }
        $this->panier->recalculer();
        $session->set("panier", $this->panier);
        return $this->redirectToRoute('cart_index');
        // return $this->render('panier.html.twig', [
        //     'panier' => $this->panier,
        // ]);

    }

    #[Route('/commanderPanier', name: 'commanderPanier')]
    public function commanderPanierAction(Request $request): Response
    {
        $searchDto ??= new SearchDto();
        $session = $request->getSession();
        $panier = $session->get('panier');
        $searchDto ??= new SearchDto();

        if (!$panier || $panier->getLignesPanier()->count() == 0) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_index');
        }

        $utilisateur = $this->getUser();
        if (!$utilisateur) {
            $this->addFlash('error', 'Vous devez être connecté pour passer une commande.');
            return $this->redirectToRoute('app_login');
        }

        $commande = new Commande();
        $commande->setUtilisateur($utilisateur);
        foreach ($panier->getLignesPanier() as $lignePanier) {
            $articleGere = $this->entityManager->getRepository(Article::class)->find($lignePanier->getArticle()->getId());
            $lignePanier->setArticle($articleGere);

            $commande->addLineItem($lignePanier);
        }
        $commande->setDate(new \DateTime());
        $commande->setStatus('Commandé');
        $commande->setTotal($panier->getTotal());
        $utilisateur->addCommande($commande);

        $this->entityManager->persist($commande);
        $this->entityManager->persist($utilisateur);
        $this->entityManager->flush();

        $session->set('panier', new Panier());
        $this->addFlash('success', 'Votre commande a été passée avec succès.');

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/checkout', name: 'checkout', methods: ['GET'])]
    public function checkoutAction(Request  $request): Response
    {
        $session = $request->getSession();
        $panier = $session->get('panier');
        $searchDto ??= new SearchDto();

        if (!$panier) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_index');
        }

        return $this->render('checkout.html.twig', [
            'searchDto' => $searchDto,
            'panier' => $panier
        ]);
    }
}
