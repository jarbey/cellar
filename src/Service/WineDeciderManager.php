<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;

use App\Entity\WineBottle;
use App\Utils\StringUtils;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Psr7;
use Symfony\Component\HttpFoundation\Response;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class WineDeciderManager extends AbstractWineSearcherManager {

	/** @var \GuzzleHttp\Client */
	private $client;

	/** @var array */
	private $results = [];

	/**
	 * SensorDataManager constructor.
	 * @param LoggerInterface $logger
	 * @param $client
	 */
	public function __construct(LoggerInterface $logger, $client) {
		parent::__construct($logger);

		/** @var \GuzzleHttp\Client $client */
		$this->client = $client;
	}

	private function connect() {
		return true;
	}

	/**
	 * @param WineBottle $wine_bottle
	 * @return string
	 */
	private function getSearchTerm(WineBottle $wine_bottle) {
		return rawurlencode(StringUtils::create($wine_bottle->getName() . ' ' . $wine_bottle->getArea()->getAreaName())->stripAccents() . ' ' . $wine_bottle->getVintage());
	}

	public function getInfos(WineBottle $wine_bottle) {
		$this->connect();

		if (!array_key_exists($wine_bottle->getId(), $this->results)) {
			$response = $this->client->get('/fr/find/recherche.php?keyword=' . $this->getSearchTerm($wine_bottle)); // Get cookie
			if ($response->getStatusCode() == Response::HTTP_OK) {
				$c = HtmlPageCrawler::create($response->getBody()->getContents());
				$tds = $c->filter('#tableauprix td');
				if ($tds->count() > 4) {
					$price = floatval(StringUtils::create($tds->getNode(3)->textContent)->substringBeforeFirst('€')->__toString());
					$grade = floatval(StringUtils::create($tds->getNode(4)->textContent)->substringBeforeFirst('/')->__toString());

					$this->results[$wine_bottle->getId()] = [$price, $grade];
				}
			}
		}

		if (array_key_exists($wine_bottle->getId(), $this->results)) {
			return $this->results[$wine_bottle->getId()];
		}

		return null;
	}

}