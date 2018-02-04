<?php

namespace App\Api;

use App\Entity\Db;
use App\Entity\Sensor;
use App\Entity\SensorData;
use App\Entity\SensorDataGroup;
use App\Exception\SensorNotFoundException;
use App\Model\ApiResult;
use App\Repository\DbRepository;
use App\Repository\SensorRepository;
use App\Service\RrdManager;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Response;

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
	 * @Put("{db_id}/{date}", requirements={"db_id" = "\d+", "date" = "\d+"})
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

}