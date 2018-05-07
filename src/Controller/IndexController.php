<?php

namespace App\Controller;

use App\Entity\Bottle;
use App\Entity\Db;
use App\Repository\DbRepository;
use App\Service\WineManager;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Created by PhpStorm.
 * User: jarbey
 * Date: 05/02/2018
 * Time: 22:40
 */
class IndexController extends Controller {

	/**
	 * @Route("/{id}", name="home", requirements={"id" = "\d+"})
	 */
	public function home($id, Request $request, DbRepository $db_repository) {
		/** @var Db $db */
		$db = $db_repository->find($id);
		if ($db == null) {
			throw new EntityNotFoundException();
		}

		$t = $request->query->get('t', 0);
		if ($t > 0) {
			$date = new \DateTime('@' . $t);
		} else {
			$date = new \DateTime();
			$date->sub(new \DateInterval('PT1H'));
		}

		return $this->render('home.html.twig', [
			'db' => $db,
			'date' => $date,
			'ws_url' => 'cellar.arbey.fr/ws',
		]);
	}

	/**
	 * @Route("/test", name="test")
	 */
	public function test(WineManager $wine_manager) {
		$wine_manager->importCSV(file(__DIR__ . '/../../cavusvinifera-export.csv'));

		return $this->render('test.html.twig', []);
	}
}