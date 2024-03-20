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
				if ( !isset ($book['volumeInfo']['authors'][0])
					|| !isset ($book['volumeInfo']['publishedDate'])
					|| !isset ($book['volumeInfo']['industryIdentifiers'][0]['identifier'])
					|| !isset ($book['volumeInfo']['pageCount'])
					|| !isset ($book['volumeInfo']['description'])
					|| !isset ($book['saleInfo']['listPrice']['amount'])
					|| !isset ($book['volumeInfo']['imageLinks']['thumbnail'])
					|| !isset ($book['volumeInfo']['categories'][0])
				) {
					continue;
				}

				$livre = new Livre();
				$livre->setId($book['id']);
				$livre->setTitre($book['volumeInfo']['title']);
				$livre->setAuteur($book['volumeInfo']['authors'][0]);
				$livre->setEditeur($book['volumeInfo']['publisher']);
				$livre->setDateDePublication($book['volumeInfo']['publishedDate']);
				$livre->setIsbn($book['volumeInfo']['industryIdentifiers'][0]['identifier']);
				$livre->setNbPages($book['volumeInfo']['pageCount']);
				$livre->setResume($book['volumeInfo']['description']);
				$livre->setPrix($book['saleInfo']['listPrice']['amount']);
				$livre->setDisponibilite(rand(5, 15));
				$livre->setImage($book['volumeInfo']['imageLinks']['thumbnail']);
				$livre->setCategorie($book['volumeInfo']['categories'][0]);

				$manager->persist($livre);
			}

			$manager->flush();
		} else {
			$this->logger->info("La base de données contient déjà des livres.");
		}
	}

}
