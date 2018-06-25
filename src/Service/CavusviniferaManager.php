<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;

use App\Entity\WineBottle;
use App\Model\WinePairingResult;
use App\Model\WinePairingResults;
use App\Repository\WineAreaRepository;
use App\Repository\WineColorRepository;
use App\Utils\StringUtils;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Psr7;
use Symfony\Component\HttpFoundation\Response;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class CavusviniferaManager extends AbstractManager {

	/** @var \GuzzleHttp\Client */
	private $client;

	/** @var WineManager */
	private $wine_manager;

	/**
	 * SensorDataManager constructor.
	 * @param LoggerInterface $logger
	 * @param $client
	 * @param WineManager $wine_manager
	 */
	public function __construct(LoggerInterface $logger, $client, WineManager $wine_manager) {
		parent::__construct($logger);

		/** @var \GuzzleHttp\Client $client */
		$this->client = $client;

		$this->wine_manager = $wine_manager;
	}

	public function import() {
		$this->wine_manager->importCSV(explode("\n", $this->getExport()));
	}

	public function getExport() {
		// Login
		$this->client->post('/fr/login.php', [
			'form_params' => [
				'login' => 'psio',
				'passwd' => 'Psiovin',
				'x' => '11',
				'y' => '10',
			]
		]);

		// Export page
		$response = $this->client->post('/fr/exporter.php', [
			'headers' => [
				'Accept-Encoding' => 'gzip, deflate',
			],
			'form_params' => [
				'type_export' => 'synthesis',
				'x' => '39',
				'y' => '12',
			],
		]);

		return $response->getBody()->getContents();
	}
}