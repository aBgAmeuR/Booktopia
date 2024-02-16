<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Psr\Log\LoggerInterface;

use App\Entity\Catalogue\Article;
use App\Entity\Panier\Panier;
use App\Entity\Panier\LignePanier;

use Doctrine\ORM\EntityManagerInterface;

class PanierController extends AbstractController
{
	private EntityManagerInterface $entityManager;
	private LoggerInterface $logger;
	
	private Panier $panier;
	
	public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)  {
		$this->entityManager = $entityManager;
		$this->logger = $logger;
	}
	
    #[Route('/ajouterLigne', name: 'ajouterLigne')]
    public function ajouterLigneAction(Request $request): Response
    {
		$session = $request->getSession() ;
		if (!$session->isStarted())
			$session->start() ;	
		if ($session->has("panier"))
			$this->panier = $session->get("panier") ;
		else
			$this->panier = new Panier() ;
		$article = $this->entityManager->getReference("App\Entity\Catalogue\Article", $request->query->get("id"));
		$this->panier->ajouterLigne($article) ;
		$session->set("panier", $this->panier) ;
		return $this->render('panier.html.twig', [
            'panier' => $this->panier,
        ]);
    }
	
    #[Route('/supprimerLigne', name: 'supprimerLigne')]
    public function supprimerLigneAction(Request $request): Response
    {
		$session = $request->getSession() ;
		if (!$session->isStarted())
			$session->start() ;	
		if ($session->has("panier"))
			$this->panier = $session->get("panier") ;
		else
			$this->panier = new Panier() ;
		$this->panier->supprimerLigne($request->query->get("id")) ;
		$session->set("panier", $this->panier) ;
		if (sizeof($this->panier->getLignesPanier()) === 0)
			return $this->render('panier.vide.html.twig');
		else
			return $this->render('panier.html.twig', [
				'panier' => $this->panier,
			]);
    }
	
    #[Route('/recalculerPanier', name: 'recalculerPanier', methods: ["GET", "POST"])]
    public function recalculerPanierAction(Request $request): Response
    {
		$session = $request->getSession() ;
		if (!$session->isStarted())
			$session->start() ;	
		if ($session->has("panier"))
			$this->panier = $session->get("panier") ;
		else
			$this->panier = new Panier() ;
		$it = $this->panier->getLignesPanier()->getIterator();
		while ($it->valid()) {
			$ligne = $it->current();
			$article = $ligne->getArticle() ;
			// cart[1141555897821]["qty"]=4   https://symfony.com/doc/6.4/components/http_foundation.html
			$ligne->setQuantite($request->request->all("cart")[$article->getId()]["qty"]);
			$ligne->recalculer() ;
			$it->next();
		}
		$this->panier->recalculer() ;
		$session->set("panier", $this->panier) ;
		return $this->render('panier.html.twig', [
            'panier' => $this->panier,
        ]);
    }
	 
    #[Route('/accederAuPanier', name: 'accederAuPanier')]
    public function accederAuPanierAction(Request $request): Response
    {
		$session = $request->getSession() ;
		if (!$session->isStarted())
			$session->start() ;	
		if ($session->has("panier"))
			$this->panier = $session->get("panier") ;
		else
			$this->panier = new Panier() ;
		if (sizeof($this->panier->getLignesPanier()) === 0)
			return $this->render('panier.vide.html.twig');
		else
			return $this->render('panier.html.twig', [
				'panier' => $this->panier,
			]);
    }
	
    #[Route('/commanderPanier', name: 'commanderPanier')]
    public function commanderPanierAction(Request $request): Response
    {
		return $this->render('commande.html.twig');
    }
}
