<?php

trait ValidatesConstructionStage
{
    /**
     * Validates all the construction stage given fields
     * 
     * @param array|object $fields The construction stage fields array or data object
     * @return array An associative array with the validation error messages
     */
    public function validate($fields)
    {
        $validationErrors = [];

        if (is_object($fields)) {
            $fields = get_object_vars($fields);
        }

        if (is_array($fields)) {
            foreach ($fields as $field => $value) {
                switch ($field) {
                    case 'startDate':
                        if ($error = $this->validateStartDate($value))
                            $validationErrors[$field]['errors'] = [$error];
                        break;
                    case 'endDate':
                        if ($error = $this->validateEndDate($value))
                            $validationErrors[$field]['errors'] = [$error];
                        break;
                    case 'durationUnit':
                        if ($error = $this->validateDurationUnit($value))
                            $validationErrors[$field]['errors'] = [$error];
                        break;
                    case 'color':
                        if ($error = $this->validateColor($value))
                            $validationErrors[$field]['errors'] = [$error];
                        break;
                    case 'externalId':
                        if ($error = $this->validateExternalId($value))
                            $validationErrors[$field]['errors'] = [$error];
                        break;
                    case 'status':
                        if ($error = $this->validateStatus($value))
                            $validationErrors[$field]['errors'] = [$error];
                        break;
                }
            }

            if (array_key_exists('startDate', $fields) && array_key_exists('endDate', $fields)) {
                if ($this->isStartDateGreaterThanEndDate($fields['startDate'], $fields['endDate'])) {
                    $message = "The start date must not be later than end date.";
                    if (array_key_exists('startDate', $validationErrors)) {
                        $validationErrors['startDate']['errors'][] = $message;
                    } else {
                        $validationErrors['startDate']['errors'] = [$message];
                    }
                }
            }
        }

        return $validationErrors;
    }

    /**
     * Validates construction stage name field.
     * 
     * @param string $name
     * @return string|null
     */
    public function validateName($name)
    {
        if (!(is_string($name) && strlen($name) <= 255)) {
            return "The name field must be a string of 255 characters at most.";
        }
    }

    /**
     * Validates construction stage start date field.
     * 
     * @param string $startDate
     * @return string|null
     */
    public function validateStartDate($startDate)
    {
        if (!$this->isISO8601DateFormat($startDate)) {
            return  "The start date must be in ISO8601 format.";
        }
    }

    /**
     * Validates construction stage endDate field.
     * 
     * @param string $endDate
     * @return string|null
     */
    public function validateEndDate($endDate)
    {
        if ($endDate) {
            if (!$this->isISO8601DateFormat($endDate)) {
                return "The end date must be in ISO8601 format.";
            }
        }
    }

    /**
     * Validates construction stage durationUnit field.
     * 
     * @param string $durationUnit Possible values ['HOURS', 'DAYS', 'WEEKS']
     * @return string|null
     */
    public function validateDurationUnit($durationUnit)
    {
        if (!in_array($durationUnit, ['HOURS', 'DAYS', 'WEEKS'])) {
            return 'The specified duration unit field is invalid.';
        }
    }

    /**
     * Validates construction stage color field.
     * 
     * @param string $color
     * @return string|null
     */
    public function validateColor($color)
    {
        $pattern = "/^(\#[\da-f]{3}|\#[\da-f]{6})$/i";
        $matched = preg_match($pattern, $color);

        if ($color && !$matched) {
            return 'The color field must be a valid HEX color.';
        }
    }

    /**
     * Validates construction stage externalId field.
     * 
     * @param string $externalId
     * @return string|null
     */
    public function validateExternalId($externalId)
    {
        if ($externalId) {
            if (!(is_string($externalId) && strlen($externalId) <= 255)) {
                return "The external id field must be null or a string of 255 characters at most.";
            }
        }
    }

    /**
     * Validates construction stage status field.
     * 
     * @param string $status Possible values ['NEW', 'PLANNED', 'DELETED']
     * @return string|null
     */
    public function validateStatus($status)
    {
        if (!in_array($status, ['NEW', 'PLANNED', 'DELETED'])) {
            return 'The specified status field is invalid.';
        }
    }

    /**
     * Check if given date string is in iso8601 format.
     * 
     * @param string $date
     * @return int|false
     */
    public function isISO8601DateFormat($date)
    {
        $pattern = "/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(\.\d{3})?Z$/";
        return preg_match($pattern, $date);
    }

    /**
     * Check if start date is later than end date.
     * 
     * @param string $startDate
     * @param string $endDate
     * @return string|null
     */
    public function isStartDateGreaterThanEndDate($startDate, $endDate)
    {
        $start = strtotime($startDate);
        $end = strtotime($endDate);

        if ($start && $end)
            return $start - $end >= 0;

        return false;
    }
}
