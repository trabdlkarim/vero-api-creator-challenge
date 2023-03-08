<?php

class ConstructionStagesUpdate extends stdClass
{
	public function __construct($data)
	{
		if (is_object($data)) {

			$vars = [
				'name',
				'stardDate',
				'endDate',
				'duration',
				'durationUnit',
				'color',
				'externalId',
				'status'
			];

			foreach ($vars as $name) {
				if (isset($data->$name)) {
					$this->$name = $data->$name;
				}
			}
		}
	}
}
