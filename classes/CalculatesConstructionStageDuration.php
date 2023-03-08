<?php

trait CalculatesConstructionStageDuration
{
    /**
     * Calculates the construction stage duration given start and end dates.
     * 
     * @param string $startDate
     * @param string $endDate
     * @param string $unit
     * @return float
     */
    public function getStageDuration($startDate, $endDate, $unit = 'DAYS')
    {
        $start = strtotime($startDate);
        $end = strtotime($endDate);
        $duration = 0;

        if ($start && $end)
            $duration = $end - $start;

        switch ($unit) {
            case 'DAYS':
                $duration = $this->getDurationInDays($duration);
                break;
            case 'HOURS':
                $duration = $this->getDurationInHours($duration);
                break;
            case 'WEEKS':
                $duration = $this->getDurationInWeeks($duration);
                break;
            default:
                $duration = $this->getDurationInDays($duration);
        }

        return $duration;
    }

    /**
     * Calculates the construction stage duration in hours.
     * 
     * @param int $durationInSeconds
     * @return float
     */
    public function getDurationInHours($durationInSeconds)
    {
        if ($durationInSeconds <= 0) {
            return 0;
        }

        return round($durationInSeconds / (60 * 60), 2);
    }

    /**
     * Calculates the construction stage duration in days.
     * 
     * @param int $durationInSeconds
     * @return float
     */
    public function getDurationInDays($durationInSeconds)
    {
        return round($this->getDurationInHours($durationInSeconds) / 24, 2);
    }

    /**
     * Calculates the construction stage duration in weeks.
     * 
     * @param int $durationInSeconds
     * @return float
     */
    public function getDurationInWeeks($durationInSeconds)
    {
        return round($this->getDurationInDays($durationInSeconds) / 7, 2);
    }
}
