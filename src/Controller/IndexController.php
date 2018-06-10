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
	 *
	 * @param $id
	 * @param Request $request
	 * @param DbRepository $db_repository
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws EntityNotFoundException
	 * @throws \Exception
	 */
	public function home($id, Request $request, DbRepository $db_repository) {
		/** @var Db $db */
		$db = $db_repository->find($id);
		if ($db == null) {
			throw new EntityNotFoundException();
		}

		if ($request->query->get('from', 0) > 0) {
			$from = new \DateTime('@' . $request->query->get('from', 0), new \DateTimeZone('Europe/Paris'));
		} else {
			$from = new \DateTime();
			$from->sub(new \DateInterval('P1D'));
		}

		if ($request->query->get('to', 0) > 0) {
			$to = new \DateTime('@' . $request->query->get('to', 0), new \DateTimeZone('Europe/Paris'));
		} else {
			$to = new \DateTime();
		}

		return $this->render('home.html.twig', [
			'db' => $db,
			'from' => $from,
			'to' => $to,
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