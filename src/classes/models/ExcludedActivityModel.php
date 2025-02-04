<?php

namespace CalendarPlugin\src\classes\models;

class ExcludedActivityModel extends Model
{
    public $excludedName;
    public $excludedStartAt;
    public $excludedEndAt;
    public $excludedDate;
    public $excludedType;
    public $excludedBgColor;
    public $excludedIsActive;

    /**
     * Constructor
     * 
     * @param array data
     */
    public function __construct($data = null) {
        $this->excludedName = null;
        $this->excludedStartAt = null;
        $this->excludedEndAt = null;
        $this->excludedDate = null;
        $this->excludedType = null;
        $this->excludedBgColor = null;
        $this->excludedIsActive = false;

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
                case "excluded_activity_name":
                    $this->excludedName = $value;
                    break;
                case "excluded_activity_start_at":
                    $this->excludedStartAt = $value;
                    break;
                case "excluded_activity_end_at":
                    $this->excludedEndAt = $value;
                    break;
                case "excluded_activity_date":
                    $this->excludedDate = $value;
                    break;
                case "excluded_activity_type":
                    $this->excludedType = $value;
                    break;
                case "excluded_activity_bg_color":
                    $this->excludedBgColor = $value;
                    break;
                case "excluded_activity_is_active":
                    $this->excludedIsActive = $value;
                    break;
            }
        }
    }
}