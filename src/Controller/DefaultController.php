<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\DTO\SearchDto;

use Psr\Log\LoggerInterface;

use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends AbstractController
{
	private EntityManagerInterface $entityManager;
	private LoggerInterface $logger;
	
	public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)  {
		$this->entityManager = $entityManager;
		$this->logger = $logger;
	}

    #[Route('/', name: 'homepage')]
    public function indexAction(Request $request, LoggerInterface $logger): Response
    {
        $searchDto ??= new SearchDto();

        return $this->render('default/index.html.twig', [
            'searchDto' => $searchDto,
        ]);
    }
}
