<?php

namespace CalendarPlugin\src\classes\models;

class ActivityModel extends Model
{
    public $name;
    public $startAt;
    public $endAt;
    public $duration;
    public $isCyclic;
    public $date;
    public $day;
    public $startDateForDay;
    public $endDateForDay;
    public $bgColor;
    public $type;
    public $rawType;
    public $slot;
    public $isActive;

    /**
     * Constructor
     * 
     * @param array data
     */
    public function __construct($data = null) {
        $this->name = null;
        $this->startAt = '00:00';
        $this->endAt = '01:00';
        $this->duration = null;
        $this->isCyclic = false;
        $this->date = null;
        $this->day = null;
        $this->startDateForDay = null;
        $this->endDateForDay = null;
        $this->bgColor = null;
        $this->type = null;
        $this->slot = null;
        $this->isActive = false;

        if($data !== null && is_array($data)) {
            $this->set_data($data);
        }
    }

    /**
     * Set data to properties
     * 
     * @param array|object data
     * @return void
     */
    private function set_data($data) {
        foreach($data as $key => $value) {
            switch($key) {
                case "activity_name":
                    $this->name = $value;
                    break;
                case "activity_start_at":
                    $this->startAt = $value;
                    break;
                case "activity_end_at":
                    $this->endAt = $value;
                    break;
                case "activity_cyclic":
                    $this->isCyclic = $value;
                    break;
                case "activity_date":
                    $this->date = $value;
                    break;
                case "activity_day":
                    $this->day = $value;
                    break;
                case "activity_day_start_date":
                    $this->startDateForDay = $value;
                    break;
                case "activity_day_end_date":
                    $this->endDateForDay = $value;
                    break;
                case "activity_bg_color":
                    $this->bgColor = $value;
                    break;
                case "activity_type":
                    $this->set_activity_type($value);
                    break;
                case "activity_slot":
                    $this->slot = $value;
                    break;
                case "activity_is_active":
                    $this->isActive = $value;
                    break;
            }
        }
        $this->duration = $this->set_duration();
    }

    /**
     * Set activity type by DB data
     * 
     * @param int id
     * @return void
     */
    private function set_activity_type($id) {
        $place = $this->find($id);

        if($place !== null && isset($place->shortCode)) {
            $this->rawType = $place->shortCode;
            $this->type = $place->name;
        }
        else {
            $this->rawType = null;
            $this->type = null;
        }        
    }

    /**
     * Calculate duration time between start and end
     * 
     * @return int
     */
    private function set_duration() {
        $time1Parts = explode(":", $this->endAt);
        $time2Parts = explode(":", $this->startAt);
        
        $time1Minutes = (int)$time1Parts[0] * 60 + (int)$time1Parts[1];
        $time2Minutes = (int)$time2Parts[0] * 60 + (int)$time2Parts[1];

        $difference = $time1Minutes - $time2Minutes;
        
        if ($difference < 0) {
            $difference += 1440;
        }

        return $difference;
    }
}