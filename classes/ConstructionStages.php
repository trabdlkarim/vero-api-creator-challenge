<?php

class ConstructionStages
{
	private $db;

	public function __construct()
	{
		$this->db = Api::getDb();
	}

	/**
	 * Get all the construction stages from storage.
	 * 
	 * @return array
	 */
	public function getAll()
	{
		$stmt = $this->db->prepare("
			SELECT
				ID as id,
				name, 
				strftime('%Y-%m-%dT%H:%M:%SZ', start_date) as startDate,
				strftime('%Y-%m-%dT%H:%M:%SZ', end_date) as endDate,
				duration,
				durationUnit,
				color,
				externalId,
				status
			FROM construction_stages
		");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Get the specified construction stage from storage.
	 * 
	 * @param int $id
	 * @return array
	 */
	public function getSingle($id)
	{
		$stmt = $this->db->prepare("
			SELECT
				ID as id,
				name, 
				strftime('%Y-%m-%dT%H:%M:%SZ', start_date) as startDate,
				strftime('%Y-%m-%dT%H:%M:%SZ', end_date) as endDate,
				duration,
				durationUnit,
				color,
				externalId,
				status
			FROM construction_stages
			WHERE ID = :id
		");
		$stmt->execute(['id' => $id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Store a newly created construction stage in storage.
	 * 
	 * @param \ConstructionStagesCreate $data
	 * @return array
	 */
	public function post(ConstructionStagesCreate $data)
	{
		$stmt = $this->db->prepare("
			INSERT INTO construction_stages
			    (name, start_date, end_date, duration, durationUnit, color, externalId, status)
			    VALUES (:name, :start_date, :end_date, :duration, :durationUnit, :color, :externalId, :status)
			");
		$stmt->execute([
			'name' => $data->name,
			'start_date' => $data->startDate,
			'end_date' => $data->endDate,
			'duration' => $data->duration,
			'durationUnit' => $data->durationUnit,
			'color' => $data->color,
			'externalId' => $data->externalId,
			'status' => $data->status,
		]);
		return $this->getSingle($this->db->lastInsertId());
	}

	/**
	 * Update the specified construction stage in storage.
	 * 
	 * @param \ConstructionStagesUpdate $data
	 * @param int $id
	 * @return array
	 */
	public function patch(ConstructionStagesUpdate $data, $id)
	{
		if (isset($data->status) && !in_array($data->status, ['NEW', 'PLANNED', 'DELETED'])) {
			return [
				'error' => 'The specified status is invalid',
			];
		}

		$vars = get_object_vars($data);

		if (count($vars) == 0) {
			http_response_code(400);
		}

		$sql = "UPDATE construction_stages SET ";
		$params = [
			'id' => $id,
		];

		foreach ($vars as $name => $value) {
			switch ($name) {
				case 'startDate':
					$params['start_date'] = $value;
					$sql .= "start_date = :start_date, ";
					break;
				case 'endDate':
					$params['end_date'] = $value;
					$sql .= "end_date = :end_date, ";
					break;
				default:
					$params[$name] = $data->$name;
					$sql .= $name . " = :" . $name . ", ";
			}
		}

		$sql = trim($sql, ', ');
		$sql .= " WHERE ID = :id";

		$stmt = $this->db->prepare($sql);
		$stmt->execute($params);

		return $this->getSingle($id);
	}

	/**
	 * Delete the specified resource by setting
	 * its status to DELETED.
	 * 
	 * @param int $id
	 * @return array
	 */
	public function delete($id)
	{
		$stmt = $this->db->prepare("
		    UPDATE construction_stages 
		    SET status = 'DELETED' 
		    WHERE ID = :id
		");

		$stmt->execute(['id' => $id]);

		return [
			'success' => 'The specified resource successfully deleted'
		];
	}
}
