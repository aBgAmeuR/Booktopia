<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Psr\Log\LoggerInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Catalogue\Livre;
use App\Entity\Catalogue\Musique;
use App\Entity\Catalogue\Piste;

class RechercheController extends AbstractController
{
	private EntityManagerInterface $entityManager;
	private LoggerInterface $logger;
	
	public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)  {
		$this->entityManager = $entityManager;
		$this->logger = $logger;
	}
	
    #[Route('/afficheRecherche', name: 'afficheRecherche')]
    public function afficheRechercheAction(Request $request): Response
    {
		$query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Article a");
		$articles = $query->getResult();
		return $this->render('recherche.html.twig', [
            'articles' => $articles,
        ]);
    }
	
    #[Route('/afficheRechercheParMotCle', name: 'afficheRechercheParMotCle')]
    public function afficheRechercheParMotCleAction(Request $request): Response
    {
		//$query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Article a "
		//										  ." where a.titre like :motCle");
		//$query->setParameter("motCle", "%".$request->query->get("motCle")."%") ;
		$query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Article a "
												  ." where a.titre like '%".addslashes($request->query->get("motCle"))."%'");
		$articles = $query->getResult();
		return $this->render('recherche.html.twig', [
            'articles' => $articles,
        ]);
    }
}
