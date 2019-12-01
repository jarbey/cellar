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

    /**
     * @return int
     */
	public function import() {
	    // Clear existing data
        $this->wine_manager->clear();

        $data = $first_line = [];
        $is_first = true;
        $nb_cols = 0;
        $lines_done = [];
        foreach (explode("\n", $this->getExport()) as $line) {
            if (!in_array($line, $lines_done)) {
                if ($is_first) {
                    $is_first = false;
                    $first_line = str_getcsv($line, "\t");
                    $nb_cols = count($first_line);
                } else {
                    $line_data = [];
                    $raw_line_data = str_getcsv($line, "\t");
                    if (count($raw_line_data) == $nb_cols) {
                        foreach ($raw_line_data as $key => $value) {
                            $line_data[$first_line[$key]] = $value;
                        }
                        $data[] = $line_data;
                    }
                }
                $lines_done[] = $line;
            }
        }

        // Create
        $total = 0;
        foreach ($data as $stock) {
            list($volume, $unit) = explode(' ', $stock['volume']);
            $volume = floatval(str_replace(',', '.', $volume));
            if ($unit == 'l') $volume *= 100;

            $this->wine_manager->import($volume, $stock['label'], $stock['country_label'], $stock['subregion_label'], $stock['area_label'], $stock['nomCru'], $stock['millesime'],
                $stock['garde_min'], $stock['garde_max'], $stock['garde_optimum'], $stock['date_achat'], $stock['prix'], $stock['quantite_courante'], $stock['quantite_achat'],
                $stock['comment'], $stock['lieu_achat'], $stock['canal_vente']);

            $total += $stock['quantite_courante'];
        }

        return $total;
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
				'type_export' => 'raked',
				'x' => '39',
				'y' => '12',
			],
		]);

		return $response->getBody()->getContents();
	}
}