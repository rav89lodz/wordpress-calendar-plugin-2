<?php

namespace CalendarPlugin\src\classes\services;

class SearchService
{
    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     * Serach data by param and option
     * 
     * @param array|null data
     * @param string searchParam
     * @param string searchOption
     * @param string phrase
     * @return array|null
     */
    public static function search($data, $searchParam, $searchOption, $phrase) {
        if($searchParam === null ||$searchOption === null || $phrase === null) {
            return $data;
        }

        if($data === null || count($data) < 1) {
            return $data;
        }

        if(! property_exists($data[0], $searchParam) && $searchParam !== "dates") {
            return $data;
        }

        if($searchParam === "dates") {
            $service = new LanguageService('days');
            foreach($data as $activity) {
                $days = $activity->date;
                if($activity->date === null && $activity->day !== null) {
                    $days = str_replace([
                        'monday',
                        'tuesday',
                        'wednesday',
                        'thursday',
                        'friday',
                        'saturday',
                        'sunday'
                    ], [
                        $service->langData['monday'],
                        $service->langData['tuesday'],
                        $service->langData['wednesday'],
                        $service->langData['thursday'],
                        $service->langData['friday'],
                        $service->langData['saturday'],
                        $service->langData['sunday']
                    ], $activity->day);
                }
                $activity->temp_days = $days;
            }
            $searchParam = "temp_days";
        }

        switch($searchOption) {
            case 'option_serach_1':
                return self::searchDataContains($data, $searchParam, $phrase);
            case 'option_serach_2':
                return self::searchDataStartsWith($data, $searchParam, $phrase);
            case 'option_serach_3':
                return self::searchDataEquals($data, $searchParam, $phrase);
        }
        
        return $data;
    }

    /**
     * Serach data contains phrase
     * 
     * @param array data
     * @param string searchParam
     * @param string phrase
     * @return array
     */
    private static function searchDataContains($data, $searchParam, $phrase) {
        $toReturn = [];
        $phrase = strtolower($phrase);
        foreach($data as $element) {
            if(str_contains(strtolower($element->$searchParam), $phrase)) {
                $toReturn[] = $element;
            }
        }
        return $toReturn;
    }

    /**
     * Serach data starts with phrase
     * 
     * @param array data
     * @param string searchParam
     * @param string phrase
     * @return array
     */
    private static function searchDataStartsWith($data, $searchParam, $phrase) {
        $toReturn = [];
        $phrase = strtolower($phrase);
        foreach($data as $element) {
            if(str_starts_with(strtolower($element->$searchParam), $phrase)) {
                $toReturn[] = $element;
            }
        }
        return $toReturn;
    }

    /**
     * Serach data equals phrase
     * 
     * @param array data
     * @param string searchParam
     * @param string phrase
     * @return array
     */
    private static function searchDataEquals($data, $searchParam, $phrase) {
        $toReturn = [];
        $phrase = strtolower($phrase);
        foreach($data as $element) {
            if(strtolower($element->$searchParam) == $phrase) {
                $toReturn[] = $element;
            }
        }
        return $toReturn;
    }
}
