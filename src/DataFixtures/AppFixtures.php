<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Catalogue\Livre;

use Psr\Log\LoggerInterface;

class AppFixtures extends Fixture
{
	protected $logger;

	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	public function load(ObjectManager $manager): void
	{
		if (count($manager->getRepository("App\Entity\Catalogue\Article")->findAll()) == 0) {
			$googleAPI = new GoogleAPI();
			$books = $googleAPI->getBooks();


			foreach ($books['items'] as $book) {
				$livre = new Livre();
				$livre->setId($book['id']);
				$livre->setTitre($book['volumeInfo']['title']);
				$livre->setAuteur($book['volumeInfo']['authors'][0]);
				$livre->setEditeur($book['volumeInfo']['publisher']);
				if (isset($book['volumeInfo']['publishedDate']))
					$livre->setDateDePublication($book['volumeInfo']['publishedDate']);
				if (isset($book['volumeInfo']['industryIdentifiers'][0]['identifier']))
					$livre->setIsbn($book['volumeInfo']['industryIdentifiers'][0]['identifier']);
				if (isset($book['volumeInfo']['pageCount']))
					$livre->setNbPages($book['volumeInfo']['pageCount']);
				if (isset($book['volumeInfo']['description']))
					$livre->setResume($book['volumeInfo']['description']);
				$livre->setPrix($book['saleInfo']['listPrice']['amount']);
				$livre->setDisponibilite($book['saleInfo']['retailPrice']['amount']);
				$livre->setImage($book['volumeInfo']['imageLinks']['thumbnail']);

				$manager->persist($livre);
			}

			$manager->flush();
		} else {
			$this->logger->info("La base de données contient déjà des livres.");
		}
	}
}
