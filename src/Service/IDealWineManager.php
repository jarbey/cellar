<?php
/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 25/12/2017
 * Time: 12:15
 */

namespace App\Service;

use App\Utils\StringUtils;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Psr7;

class IDealWineManager extends AbstractManager {

	/** @var \GuzzleHttp\Client $client */
	private $client;

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

	public function connect() {
		$response = $this->client->get('/fr/my_idealwine/login.jsp'); // Get cookie
		echo '-> ' . $response->getStatusCode();

		$response = $this->client->post('/fr/my_idealwine/login.jsp', [
			'form_params' => [
				'ident' => 'Psio',
				'dest' => 'fr/my_idealwine/accueil_profil.jsp',
				'enchere' => 'null',
				'pswd' => 'p8jke1s7',
				'ok' => 'Connexion',
			]
		]);
		echo '-> ' . $response->getStatusCode();

		$response = $this->client->get('/fr/prix-vin/738-1985-Bouteille-Bordeaux-Sauternes-Chateau-d-Yquem-1er-Cru-Classe-Superieur-Blanc-Liquoreux.jsp'); // Get cookie
		echo '-> ' . $response->getStatusCode();
		echo '=> ' . StringUtils::create($response->getBody()->getContents())->substringBetween('price-cote"><strong><span>', '<');
	}

}