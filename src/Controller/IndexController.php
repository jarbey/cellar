<?php

namespace App\Controller;

use App\Entity\Bottle;
use App\Entity\Db;
use App\Repository\DbRepository;
use App\Service\CavusviniferaManager;
use App\Service\PlatsnetvinsManager;
use App\Service\WineManager;
use App\Service\WinePairingManager;
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
	 * @param WineManager $wineManager
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws EntityNotFoundException
	 * @throws \Exception
	 */
	public function home($id, Request $request, DbRepository $db_repository, WineManager $wineManager) {
		/** @var Db $db */
		$db = $db_repository->find($id);
		if ($db == null) {
			throw new EntityNotFoundException();
		}

		$stats_color = [];
		foreach ($wineManager->getWineColorRepository()->findAll() as $wine_color) {
			$stats_color[$wine_color->getName()] = $wineManager->getWineBottleRepository()->getNbWineBottleColor($wine_color);
		}

		return $this->render('home.html.twig', [
			'db' => $db,
			'ws_url' => 'cellar.arbey.fr/ws',
			'stats_color' => $stats_color,
		]);
	}

    /**
     * @Route("/{id}/temperature", name="temperature", requirements={"id" = "\d+"})
     *
     * @param $id
     * @param Request $request
     * @param DbRepository $db_repository
     * @param WineManager $wineManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws EntityNotFoundException
     * @throws \Exception
     */
    public function temperature($id, Request $request, DbRepository $db_repository, WineManager $wineManager) {
        /** @var Db $db */
        $db = $db_repository->find($id);
        if ($db == null) {
            throw new EntityNotFoundException();
        }

        if ($request->query->get('from', 0) > 0) {
            $from = new \DateTime('@' . $request->query->get('from', 0), new \DateTimeZone('UTC'));
        } else {
            $from = new \DateTime();
            $from->sub(new \DateInterval('P1D'));
        }

        if ($request->query->get('to', 0) > 0) {
            $to = new \DateTime('@' . $request->query->get('to', 0), new \DateTimeZone('UTC'));
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
     * @Route("/{id}/pairing", name="pairing", requirements={"id" = "\d+"})
     *
     * @param $id
     * @param Request $request
     * @param DbRepository $db_repository
     * @param PlatsnetvinsManager $platsnetvins_manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws EntityNotFoundException
     * @throws \Exception
     */
    public function pairing($id, Request $request, DbRepository $db_repository, WinePairingManager $wine_pairing_manager) {
        /** @var Db $db */
        $db = $db_repository->find($id);
        if ($db == null) {
            throw new EntityNotFoundException();
        }

        $search = $request->query->get('search');
        if (!$search) {
            throw new EntityNotFoundException();
        }

        $raw_result = $wine_pairing_manager->getWinePairing($search);

        return $this->render('pairing.html.twig', [
            'db' => $db,
            'pairing' => $raw_result,
        ]);
    }

	/**
	 * @Route("/{id}/list", name="cellar_list", requirements={"id" = "\d+"})
	 *
	 * @param $id
	 * @param WineManager $wineManager
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws EntityNotFoundException
	 * @throws \Exception
	 */
	public function cellarList($id, WineManager $wineManager) {
		$wine_bottles = $wineManager->getWineBottleRepository()->findAll();

		return $this->render('cellar/list.html.twig', ['wine_bottles' => $wine_bottles]);
	}

	/**
	 * @Route("/import", name="import")
	 */
	public function import(CavusviniferaManager $cavusvinifera_manager) {
		$cavusvinifera_manager->import();
		return $this->render('import.html.twig', []);
	}

	/**
	 * @Route("/{id}/chromecast", name="chromecast", requirements={"id" = "\d+"})
	 *
	 * @param $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws EntityNotFoundException
	 * @throws \Exception
	 */
	public function chromecast($id) {
		return $this->render('chromecast.html.twig', []);
	}
}