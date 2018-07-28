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

class PlatsnetvinsManager extends AbstractWinePairingManager {

	/** @var \GuzzleHttp\Client */
	private $client;

	/** @var WineAreaRepository */
	private $wine_area_repository;

	/** @var WineColorRepository */
	private $wine_color_repository;

	/**
	 * SensorDataManager constructor.
	 * @param LoggerInterface $logger
	 * @param $client
	 * @param WineAreaRepository $wine_area_repository
	 * @param WineColorRepository $wine_color_repository
	 */
	public function __construct(LoggerInterface $logger, $client, WineAreaRepository $wine_area_repository, WineColorRepository $wine_color_repository) {
		parent::__construct($logger);

		/** @var \GuzzleHttp\Client $client */
		$this->client = $client;

		$this->wine_area_repository = $wine_area_repository;
		$this->wine_color_repository = $wine_color_repository;
	}

	public function getWineBottlesPair($food) {
		$response = $this->client->post('/', [
			'form_params' => [
				'rech' => 'rech',
				'plat' => $food,
			]
		]);
		$c = HtmlPageCrawler::create($response->getBody()->getContents());


		// Food title
		$cards = $c->filter('.card');
		if ($cards->count()) {
			$result = new WinePairingResults();

			/** @var \DOMElement $card */
			foreach ($cards as $card) {
				$classes = $this->getClass($card);
				if (in_array('cardresucrit', $classes)) {
					// New food
					$result->setFood(trim(HtmlPageCrawler::create($this->getInnerHtml($card))->filter('.lgnresucrit')->text()));
				} else if (in_array('cardresuA', $classes)) {
                    $wine_color = $this->wine_color_repository->getWineColorByName(trim(HtmlPageCrawler::create($this->getInnerHtml($card))->filter('.c3_of_7_resu')->text()));
                    $area = $this->wine_area_repository->findOneBy(['area_name' => trim(HtmlPageCrawler::create($this->getInnerHtml($card))->filter('.c1_of_7_resu .lgnresu')->text())]);
                    if ($wine_color && $area) {
                        $wine_pair_result = new WinePairingResult();
                        $wine_pair_result->setWineColor($wine_color);
                        $wine_pair_result->setWineArea($area);

                        if (StringUtils::create($this->getInnerHtml($card))->contains('ACC1')) {
                            $result->addWineBest($wine_pair_result);
                        } elseif (StringUtils::create($this->getInnerHtml($card))->contains('ACC2')) {
                            $result->addWineGood($wine_pair_result);
                        } else {
                            $result->addWine($wine_pair_result);
                        }
                    }
				}
			}

			return $result;
		}

		return null;
	}
}