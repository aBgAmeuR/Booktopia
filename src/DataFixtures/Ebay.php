<?php
namespace App\DataFixtures;

use Psr\Log\LoggerInterface;

class Ebay
{
	//private $uri_finding = "http://svcs.ebay.com/services/search/FindingService/v1";
	private string $uri_finding = "http://odp.tuxfamily.org/services/search/FindingService/v1";
	//private $api_endpoint = 'http://api.ebay.com/ws/api.dll' ;
	private string $api_endpoint = 'http://odp.tuxfamily.org/ws/api.dll';
	// https://developer.ebay.com/signin?tab=register
	private string $appid = "";
	private string $certid = "";
	private string $devid = "";
	private ?string $version;
	private string $auth_token = "";
	private string $categoryId;
	private string $format = "JSON";
	private string $cache_getitem_reponse;
	private int $cache_getitem_last_itemid;
	private LoggerInterface $logger;
	private string $globalId = "EBAY-FR";

	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
		$this->version = $this->getCurrentVersion();
		$this->setCategory();
	}

	/**
	 * Set Category
	 *
	 * @param string $category
	 *            
	 * @return Ebay
	 */
	public function setCategory(string $category = "CDs"): Ebay
	{
		$this->categoryId = $this->getParentCategoryIdByName($category);

		return $this;
	}

	/**
	 * Get id of the parent category
	 *
	 * @param string $category
	 *            
	 * @return id
	 */
	public function getParentCategoryIdByName(string $category = "CDs"): int
	{
		if ($category == "Books" && $this->globalId == "EBAY-US") {
			$categoryId = 265; // Books EBAY-US !
		}
		if ($category == "Livres" && $this->globalId == "EBAY-FR") {
			$categoryId = 267; // Livres EBAY-FR
		}
		if ($category == "CDs" && $this->globalId == "EBAY-US") {
			$categoryId = 11233; // Books EBAY-US !
		}
		if ($category == "CDs" && $this->globalId == "EBAY-FR") {
			$categoryId = 11233; // Livres EBAY-FR
		}

		return $categoryId;
	}

	/**
	 * Get id of the parent category
	 *
	 * @param int|string $id
	 *            
	 * @return id
	 */
	public function getParentCategoryIdById(int|string $id): int
	{
		// https://pages.ebay.fr/categorychanges/
		$tabIdCategories = array();
		$tabIdCategories[11233] = 11233; // Musique, CD, vinyles
		$tabIdCategories[176984] = 11233; // CD
		$tabIdCategories[176985] = 11233; // Vinyles
		$tabIdCategories[176983] = 11233; // Cassettes audio

		$tabIdCategories[267] = 267; // Livres, BD, revues
		$tabIdCategories[29223] = 267; // Livres anciens, de collection
		$tabIdCategories[171219] = 267; // Fiction

		// https://pages.ebay.com/sellerinformation/news/categorychanges.html
		$tabIdCategories[11233] = 11233; // Music
		$tabIdCategories[176984] = 11233; // CDs
		$tabIdCategories[176985] = 11233; // Vinyl Records
		$tabIdCategories[176983] = 11233; // Cassettes

		$tabIdCategories[265] = 265; // Books & Magazines
		$tabIdCategories[261186] = 265; // Books

		$idParent = 0;
		if (array_key_exists($id, $tabIdCategories)) {
			$idParent = $tabIdCategories[$id];
		}

		return $idParent;
	}

	/**
	 * Get Current Version
	 *
	 * @return string of the current eBay Finding API version
	 */
	private function getCurrentVersion(): ?string
	{
		$uri = sprintf(
			"%s?OPERATION-NAME=getVersion&SECURITY-APPNAME=%s&RESPONSE-DATA-FORMAT=%s",
			$this->uri_finding,
			$this->appid,
			$this->format
		);

		$response = $this->curl($uri);

		if ($response == null) {
			return null;
		} else {
			return json_decode($response)->getVersionResponse[0]->version[0];
		}
	}

	/**
	 * Find items of products
	 * Allows you to search for eBay products based on keywords and id category.  
	 *
	 * @param string $keywords
	 * @param int $entries_per_page
	 *
	 * @return string $result in xml format ou null
	 */
	public function findItemsAdvanced(string $keywords = 'Harry Potter', int $entries_per_page = 3): ?string
	{
		// https://www.developer.ebay.com/DevZone/finding/CallRef/findItemsAdvanced.html
		$result = null;
		$call_name = 'findItemsAdvanced';
		$xml_request = '<?xml version="1.0" encoding="utf-8" ?>
						<' . $call_name . 'Request xmlns="http://www.ebay.com/marketplace/search/v1/services">
							<categoryId>' . $this->categoryId . '</categoryId>
							<keywords>' . $keywords . '</keywords>
							<paginationInput>
							  <entriesPerPage>' . $entries_per_page . '</entriesPerPage>
							  <pageNumber>1</pageNumber>
							</paginationInput>
						</' . $call_name . 'Request>';
		$headers = array(
			'CONTENT-TYPE: text/xml',
			'X-EBAY-SOA-OPERATION-NAME: ' . $call_name,
			'X-EBAY-SOA-SECURITY-APPNAME: ' . $this->appid,
			'X-EBAY-SOA-GLOBAL-ID: ' . $this->globalId,
		);
		$response = $this->curl($this->uri_finding, "POST", $headers, $xml_request);

		if ($response == null) {
			return null;
		} else {
			$dom = new \DOMDocument();
			$dom->loadXML($response);

			var_dump($dom);

			$element = $dom->getElementsByTagName($call_name . 'Response')->item(0);

			if ($element->getElementsByTagName('ack')->item(0)->nodeValue == "Success") {
				$result = $dom->saveXML($element->getElementsByTagName('searchResult')->item(0));
			}

			return $result;
		}
	}

	/**
	 * Get ItemSpecific
	 * Obtains item specifics
	 * 
	 * @param string $name
	 * @param string or int $itemId (specific element id)
	 *
	 * @return string $result (value of specific element) ou null
	 */
	public function getItemSpecific(string $name = 'Author', string $itemId = '114866016241'): ?string
	{
		// https://developer.ebay.com/devzone/xml/docs/reference/ebay/getitem.html
		$result = null;
		$call_name = 'GetItem';
		if (is_numeric((int) $itemId)) {
			$xml_request = '<?xml version="1.0" encoding="utf-8" ?>
							<' . $call_name . 'Request xmlns="urn:ebay:apis:eBLBaseComponents">
							<RequesterCredentials>
								<eBayAuthToken>' . $this->auth_token . '</eBayAuthToken>
							</RequesterCredentials>
							<IncludeItemSpecifics>true</IncludeItemSpecifics>
							<ItemID>' . $itemId . '</ItemID>
							</' . $call_name . 'Request>';
			$headers = array(
				'X-EBAY-API-COMPATIBILITY-LEVEL: 753',
				'X-EBAY-API-DEV-NAME: ' . $this->devid,
				'X-EBAY-API-APP-NAME: ' . $this->appid,
				'X-EBAY-API-CERT-NAME: ' . $this->certid,
				'X-EBAY-API-CALL-NAME: ' . $call_name,
				'X-EBAY-SOA-GLOBAL-ID: ' . $this->globalId,
				'X-EBAY-API-SITEID: 0',
			);

			if (isset($this->cache_getitem_last_itemid)) {
				if ($this->cache_getitem_last_itemid == $itemId) {
					$response = $this->cache_getitem_reponse;
				} else {
					$response = $this->curl($this->api_endpoint, "POST", $headers, $xml_request);
				}
			} else {
				$response = $this->curl($this->api_endpoint, "POST", $headers, $xml_request);
			}

			if ($response != null) {
				$this->cache_getitem_last_itemid = $itemId;
				$this->cache_getitem_reponse = $response;

				$dom = new \DOMDocument();
				$dom->loadXML($response);

				$element = $dom->getElementsByTagName($call_name . 'Response')->item(0);

				if ($element->getElementsByTagName('Ack')->item(0)->nodeValue == "Success") {
					foreach ($element->getElementsByTagName('Item')->item(0)->getElementsByTagName('NameValueList') as $nameValueList) {
						if ($nameValueList->getElementsByTagName('Name')->item(0)->nodeValue == $name) {
							$result = $nameValueList->getElementsByTagName('Value')->item(0)->nodeValue;
						}
					}
				}
			}
		}
		return $result;
	}

	/**
	 * cURL
	 * Standard cURL function to run GET & POST requests
	 * 
	 * @param string $url
	 * @param string $method
	 * @param array $headers
	 * @param string $postvals
	 *
	 * @exception string
	 * @return string $response
	 */
	private function curl(string $url, string $method = 'GET', array $headers = null, string $postvals = null): ?string
	{
		$ch = curl_init($url);

		if ($method == 'GET') {
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		} else {
			$options = array(
				CURLOPT_HEADER => false,
				CURLINFO_HEADER_OUT => true,
				CURLOPT_VERBOSE => true,
				CURLOPT_HTTPHEADER => $headers,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => $postvals,
				CURLOPT_CUSTOMREQUEST => $method,
				CURLOPT_TIMEOUT => 20
			);
			curl_setopt_array($ch, $options);
		}

		$response = curl_exec($ch);
		$erreur = "";
		if (curl_errno($ch)) {
			$erreur = curl_error($ch);
		}
		curl_close($ch);

		if ($erreur != "") {
			$this->logger->error($erreur);
			return null;
		} else {
			return $response;
		}
	}
}
?>