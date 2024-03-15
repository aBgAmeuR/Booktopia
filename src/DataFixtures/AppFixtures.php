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
			$books1 = $googleAPI->getBooks(50);
			$books2 = $googleAPI->getBooks(150);
			$books3 = $googleAPI->getBooks(250);
			$books = array_merge($books1['items'], $books2['items'], $books3['items']);

			foreach ($books as $book) {
				$livre = new Livre();
				$livre->setId($book['id']);
				$livre->setTitre($book['volumeInfo']['title']);
				if (isset($book['volumeInfo']['authors'][0]))
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
				if (isset($book['saleInfo']['listPrice']['amount']))
					$livre->setPrix($book['saleInfo']['listPrice']['amount']);
				else continue;
				if (isset($book['saleInfo']['retailPrice']['amount']))
				$livre->setDisponibilite($book['saleInfo']['retailPrice']['amount']);
				$livre->setImage($book['volumeInfo']['imageLinks']['thumbnail']);
				if (isset($book['volumeInfo']['categories'][0]))
					$livre->setCategorie($book['volumeInfo']['categories'][0]);

				$manager->persist($livre);
			}

			$manager->flush();
		} else {
			$this->logger->info("La base de données contient déjà des livres.");
		}
	}

}
