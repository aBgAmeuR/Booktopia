<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use GuzzleHttp\Client;

use DeezerAPI\Search;

use App\Entity\Catalogue\Livre;
use App\Entity\Catalogue\Musique;
use App\Entity\Catalogue\Piste;

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
			$ebay = new Ebay($this->logger);
			// $ebay->setCategory('CDs');
			// $keywords = 'Ibrahim Maalouf' ;
			$ebay->setCategory('Livres');
			$keywords = 'Harry Potter';

			$formattedResponse = $ebay->findItemsAdvanced($keywords, 6);
			file_put_contents("ebayResponse.xml", $formattedResponse);
			$xml = simplexml_load_string($formattedResponse);

			if ($xml !== false) {
				foreach ($xml->children() as $child_1) {
					if ($child_1->getName() === "item") {
						if ($ebay->getParentCategoryIdById($child_1->primaryCategory->categoryId) == $ebay->getParentCategoryIdByName("Livres")) {
							$entityLivre = new Livre();
							$entityLivre->setId((int) $child_1->itemId);
							$title = $ebay->getItemSpecific("Book Title", $child_1->itemId);
							if ($title == null)
								$title = $child_1->title;
							$entityLivre->setTitre($title);
							$author = $ebay->getItemSpecific("Author", $child_1->itemId);
							if ($author == null)
								$author = "";
							$entityLivre->setAuteur($author);
							$entityLivre->setISBN("");
							$entityLivre->setPrix((float) $child_1->sellingStatus->currentPrice);
							$entityLivre->setDisponibilite(1);
							$entityLivre->setImage($child_1->galleryURL);
							$manager->persist($entityLivre);
							$manager->flush();
						}
						if ($ebay->getParentCategoryIdById($child_1->primaryCategory->categoryId) == $ebay->getParentCategoryIdByName("CDs")) {
							$entityMusique = new Musique();
							$entityMusique->setId((int) $child_1->itemId);
							$title = $ebay->getItemSpecific("Release Title", $child_1->itemId);
							if ($title == null)
								$title = $child_1->title;
							$entityMusique->setTitre($title);
							$artist = $ebay->getItemSpecific("Artist", $child_1->itemId);
							if ($artist == null)
								$artist = "";
							$entityMusique->setArtiste($artist);
							$entityMusique->setDateDeParution("");
							$entityMusique->setPrix((float) $child_1->sellingStatus->currentPrice);
							$entityMusique->setDisponibilite(1);
							$entityMusique->setImage($child_1->galleryURL);
							if (!isset($albums)) {
								$deezerSearch = new Search($keywords);
								$artistes = $deezerSearch->searchArtist();
								$albums = $deezerSearch->searchAlbumsByArtist($artistes[0]->getId());
							}
							$j = 0;
							$sortir = ($j == count($albums));
							$albumTrouve = false;
							while (!$sortir) {
								$titreDeezer = str_replace(" ", "", mb_strtolower($albums[$j]->title));
								$titreEbay = str_replace(" ", "", mb_strtolower($entityMusique->getTitre()));
								$titreDeezer = str_replace("-", "", $titreDeezer);
								$titreEbay = str_replace("-", "", $titreEbay);
								$albumTrouve = ($titreDeezer == $titreEbay);
								if (mb_strlen($titreEbay) > mb_strlen($titreDeezer))
									$albumTrouve = $albumTrouve || (mb_strpos($titreEbay, $titreDeezer) !== false);
								if (mb_strlen($titreDeezer) > mb_strlen($titreEbay))
									$albumTrouve = $albumTrouve || (mb_strpos($titreDeezer, $titreEbay) !== false);
								$j++;
								$sortir = $albumTrouve || ($j == count($albums));
							}
							if ($albumTrouve) {
								$tracks = $deezerSearch->searchTracksByAlbum($albums[$j - 1]->getId());
								foreach ($tracks as $track) {
									$entityPiste = new Piste();
									$entityPiste->setTitre($track->title);
									$entityPiste->setMp3($track->preview);
									$manager->persist($entityPiste);
									$manager->flush();
									$entityMusique->addPiste($entityPiste);
								}
							}
							$manager->persist($entityMusique);
							$manager->flush();
						}
					}
				}
			}
			$entityLivre = new Livre();
			$entityLivre->setId(55677821);
			$entityLivre->setTitre("Le seigneur des anneaux");
			$entityLivre->setAuteur("J.R.R. TOLKIEN");
			$entityLivre->setISBN("2075134049");
			$entityLivre->setNbPages(736);
			$entityLivre->setDateDeParution("03/10/19");
			$entityLivre->setPrix("8.90");
			$entityLivre->setDisponibilite(1);
			$entityLivre->setImage("/images/51O0yBHs+OL._SL140_.jpg");
			$manager->persist($entityLivre);
			$entityLivre = new Livre();
			$entityLivre->setId(55897821);
			$entityLivre->setTitre("Un paradis trompeur");
			$entityLivre->setAuteur("Henning Mankell");
			$entityLivre->setISBN("275784797X");
			$entityLivre->setNbPages(400);
			$entityLivre->setDateDeParution("09/10/14");
			$entityLivre->setPrix("6.80");
			$entityLivre->setDisponibilite(1);
			$entityLivre->setImage("/images/71uwoF4hncL._SL140_.jpg");
			$manager->persist($entityLivre);
			$entityLivre = new Livre();
			$entityLivre->setId(56299459);
			$entityLivre->setTitre("Dôme tome 1");
			$entityLivre->setAuteur("Stephen King");
			$entityLivre->setISBN("2212110685");
			$entityLivre->setNbPages(840);
			$entityLivre->setDateDeParution("06/03/13");
			$entityLivre->setPrix("8.90");
			$entityLivre->setDisponibilite(1);
			$entityLivre->setImage("/images/719FffADQAL._SL140_.jpg");
			$manager->persist($entityLivre);
			$manager->flush();
		}
	}
}
