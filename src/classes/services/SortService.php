<?php

namespace CalendarPlugin\src\classes\services;

use CalendarPlugin\src\classes\consts\CalendarSort;

class SortService
{
    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     * Sort data by name and vector
     * 
     * @param array|null data
     * @param string sortName
     * @param string sortVector
     * @return array|null
     */
    public static function sortBy($data, $sortName, $sortVector) {
        if($sortName === null || $sortVector === null) {
            return $data;
        }

        if($data === null || count($data) < 1) {
            return $data;
        }

        if(! property_exists($data[0], $sortName) && $sortName !== "dates") {
            return $data;
        }

        if($sortName === "dates") {
            foreach($data as $activity) {
                $days = $activity->date;
                if($activity->date === null && $activity->day !== null) {
                    $days = str_replace(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'], [91, 92, 93, 94, 95, 96, 97], $activity->day);
                }
                $activity->temp_days = $days;
            }
            $sortName = "temp_days";
        }

        usort($data, function($a, $b) use ($sortName, $sortVector) {
            $valueA = $a->$sortName;
            $valueB = $b->$sortName;

            if (is_numeric($valueA) && is_numeric($valueB)) {
                $comparison = $valueA - $valueB;
            }
            elseif (is_string($valueA) && is_string($valueB)) {
                $comparison = strcmp(strtolower($valueA), strtolower($valueB));
            }
            else {
                $comparison = $valueA <=> $valueB;
            }

            return ($sortVector === CalendarSort::ASC) ? $comparison : -$comparison;
        });
        
        return $data;
    }
}
