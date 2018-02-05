<?php

namespace App\Controller;

use App\Entity\Db;
use App\Repository\DbRepository;
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
	 * @Route("/", name="home")
	 */
	public function home(Request $request, DbRepository $db_repository) {
		/** @var Db $db */
		$db = $db_repository->find(1);


		$t = $request->query->get('t', 0);
		if ($t > 0) {
			$date = new \DateTime('@' . $t);
		} else {
			$date = new \DateTime();
		}


		return $this->render('home.html.twig', [
			'db' => $db,
			'date' => $date
		]);
	}
}