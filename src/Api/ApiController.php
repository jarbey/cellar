<?php

namespace App\Api;

use App\Entity\Db;
use App\Entity\Sensor;
use App\Entity\SensorDataGroup;
use App\Exception\SensorNotFoundException;
use App\Model\ApiResult;
use App\Repository\DbRepository;
use App\Repository\SensorRepository;
use App\Service\RrdManager;
use FOS\RestBundle\Controller\Annotations AS FOS;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class ApiController
 * @package App\Api
 */
class ApiController extends FOSRestController {

	/** @var RrdManager */
	private $rrd_manager;

	/** @var DbRepository */
	private $db_repository;

	/** @var SensorRepository */
	private $sensor_repository;

	/**
	 * ApiController constructor.
	 * @param RrdManager $rrd_manager
	 * @param DbRepository $db_repository
	 * @param SensorRepository $sensor_repository
	 */
	public function __construct(RrdManager $rrd_manager, DbRepository $db_repository, SensorRepository $sensor_repository) {
		$this->rrd_manager = $rrd_manager;
		$this->db_repository = $db_repository;
		$this->sensor_repository = $sensor_repository;
	}


	/**
	 * @FOS\Put("{db_id}/{date}", requirements={"db_id" = "\d+", "date" = "\d+"})
	 * @ParamConverter("db", options={"id" = "db_id"})
	 * @ParamConverter("sensor_data_group", class="App\Entity\SensorDataGroup", converter="fos_rest.request_body")
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns update state",
	 *     @Model(type=ApiResult::class)
	 * )
	 * @SWG\Parameter(
	 *     name="sensor_data_group",
	 *     in="body",
	 *     @SWG\Schema(
	 *         @Model(type=SensorDataGroup::class, groups={"updateSensorData"})
	 *     ),
	 *     description="SensorDataGroup object that represent the data to update"
	 * )
	 *
	 * @param Db $db
	 * @param int $date
	 * @param SensorDataGroup $sensor_data_group
	 * @return Response
	 * @throws SensorNotFoundException
	 */
	public function updateSensorDataAction(Db $db, $date, SensorDataGroup $sensor_data_group) {
		// TODO : Add Validator for values RequestParam

		// Check sensor values
		foreach ($sensor_data_group->getSensorData() as $sensor_data) {
			/** @var Sensor $sensor */
			$sensor = $this->sensor_repository->find($sensor_data->getSensor()->getId());
			if ($sensor == null) {
				throw new SensorNotFoundException('ID : ' . $sensor_data->getSensor()->getId() . ' does not exists');
			}
			$sensor_data->setSensor($sensor);
		}

		try {
			$this->rrd_manager->updateArchive($db, $sensor_data_group, $date);

			$view = $this->view(new ApiResult(ApiResult::OK, ''), 200);
		} catch (Exception $e) {
			$view = $this->view(new ApiResult(ApiResult::KO, $e->getMessage()), 500);
		}
		return $this->handleView($view);
	}

	/**
	 * @FOS\Get("{db_id}/sensors/{sensor_id}/graph/{type}", requirements={"db_id" = "\d+", "sensor_id" = "\d+", "type" = "humidity|temperature"})
	 * @ParamConverter("db", options={"id" = "db_id"})
	 * @ParamConverter("sensor", options={"id" = "sensor_id"})
	 * @FOS\QueryParam(name="from", requirements="\d+", default="0", description="Start time of graph")
	 * @FOS\QueryParam(name="to", requirements="\d+", default="0", description="End time of graph")
	 *
	 * @SWG\Response(
	 *     response=200,
	 *     description="Returns update state",
	 *     @SWG\Schema(
	 *     		type="string",
	 *  		format="binary",
	 *     )
	 * )
	 *
	 * @param Db $db
	 * @param Sensor $sensor
	 * @param string $type
	 * @param int $from
	 * @param int $to
	 * @return Response
	 * @throws SensorNotFoundException
	 * @throws \Exception
	 */
	public function graphSensorAction(Db $db, Sensor $sensor, $type, $from = 0, $to = 0) {
		if ($from > 0) {
			$from = new \DateTime('@' . $from, new \DateTimeZone('UTC'));
		} else {
			$from = new \DateTime();
			$from->sub(new \DateInterval('P1D'));
		}

		if ($to > 0) {
			$to = new \DateTime('@' . $to, new \DateTimeZone('UTC'));
		} else {
			$to = new \DateTime();
		}

		// Generate response
		$response = new Response($this->rrd_manager->graphArchive($db, $sensor, $type, $from, $to));

		// Set headers
		$response->headers->set('Cache-Control', 'no-cache');
		$response->headers->set('Content-type', 'image/png');
		// Create the disposition of the file
		$response->headers->set('Content-Disposition', $response->headers->makeDisposition(
			ResponseHeaderBag::DISPOSITION_INLINE,
			uniqid() . '.png'
		));

		return $response;
	}

}