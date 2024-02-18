<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\DTO\SearchDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

use Psr\Log\LoggerInterface;

use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    #[Route('/', name: 'homepage')]
    public function indexAction(Request $request, LoggerInterface $logger, #[MapQueryString()] ?SearchDto $searchDto = null): Response
    {
        $searchDto ??= new SearchDto();

        $query = $this->entityManager->createQuery("SELECT a FROM App\Entity\Catalogue\Article a");
        $articles = $query->getResult();
        return $this->render('home.html.twig', [
            'searchDto' => $searchDto,
            'articles' => $articles,
        ]);
    }
}
