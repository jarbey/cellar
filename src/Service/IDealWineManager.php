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

class IDealWineManager extends AbstractWineSearcherManager {

	/** @var \GuzzleHttp\Client */
	private $client;

	/** @var array */
	private $results = [];

	/** @var array */
	private $ids_cache = [];

	/** @var boolean */
	private $connected = false;

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
		if (!$this->connected) {
			$response = $this->client->get('/fr/my_idealwine/login.jsp'); // Get cookie
			if ($response->getStatusCode() == Response::HTTP_OK) {
				$response = $this->client->post('/fr/my_idealwine/login.jsp', [
					'form_params' => [
						'ident' => 'Psio',
						'dest' => 'fr/my_idealwine/accueil_profil.jsp',
						'enchere' => 'null',
						'pswd' => 'p8jke1s7',
						'ok' => 'Connexion',
					]
				]);

				if ($response->getStatusCode() == Response::HTTP_OK) {
					$this->connected = true;
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param WineBottle $wine_bottle
	 * @return string
	 */
	private function getSearchTerm(WineBottle $wine_bottle) {
		return StringUtils::create(StringUtils::create($wine_bottle->getName())->stripAccents())->replace(' ', '-');
	}

	public function getInfos(WineBottle $wine_bottle) {
		$this->connect();

		if (!array_key_exists($wine_bottle->getId(), $this->results)) {
			// Get id ideal_wine
			$key = $wine_bottle->getArea()->getId() . ';' . $wine_bottle->getName();
			if (!array_key_exists($key, $this->ids_cache)) {
				$response = $this->client->get('/fr/prix-vin/' . $this->getSearchTerm($wine_bottle) . '.jsp');
				$c = HtmlPageCrawler::create($response->getBody()->getContents());
				$trs = $c->filter('#tbResult tr:nth-child(2)');
				if ($trs->count()) {
					$this->ids_cache[$key] = StringUtils::create($trs->html())->substringAfterFirst('/fr/prix-vin/')->substringBeforeFirst('-');
				}
			}

			if (array_key_exists($key, $this->ids_cache) && ($this->ids_cache[$key] > 0)) {
				$response = $this->client->get('/fr/prix-vin/' . $this->ids_cache[$key] . '-' . $wine_bottle->getVintage() . '-Bouteille-t.jsp');
				if ($response->getStatusCode() == Response::HTTP_OK) {
					$content = $response->getBody()->getContents();
					$c = HtmlPageCrawler::create($content);
					$tds = $c->filter('.price-table p a span');
					$price = ($tds->count()) ? floatval(StringUtils::create($tds->getInnerHtml())->substringBeforeFirst('<')->__toString()) : null;

					// If price found get grade
					$grade = null;
					if ($price) {
						$raw_grades = array_filter(explode(',', StringUtils::create($content)->substringAfterFirst('type: \'radar\'')->substringAfterFirst('data: [')->substringBeforeFirst(']')));
						$grade = round(array_sum($raw_grades) / count($raw_grades), 1);
					}

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