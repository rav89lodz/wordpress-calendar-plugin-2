<?php

namespace CalendarPlugin\src\classes\models;

use CalendarPlugin\src\classes\consts\CalendarStatus;

class AddGridActivityModel extends Model
{
    public $activityUserName;
    public $activityUserEmail;
    public $activityUserPhone;
    public $activityDate;
    public $activityTimeStart;
    public $activityTimeEnd;
    public $activityName;
    public $activityStatus;

    /**
     * Constructor
     * 
     * @param array|object data
     */
    public function __construct($data = null) {
        $this->activityUserName = null;
        $this->activityUserEmail = null;
        $this->activityUserPhone = null;
        $this->activityDate = null;
        $this->activityTimeStart = null;
        $this->activityTimeEnd = null;
        $this->activityName = null;
        $this->activityStatus = CalendarStatus::CALENDAR_STATUS_REJECTED;

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
                case 'user_name_calendar_add_activity':
                    $this->activityUserName = $value;
                    break;
                case 'name_calendar_add_activity':
                    $this->activityName = $value;
                    break;
                case 'date_calendar_add_activity':
                    $this->activityDate = $value;
                    break;
                case 'user_email_calendar_add_activity':
                    $this->activityUserEmail = $value;
                    break;
                case 'user_phone_calendar_add_activity':
                    $this->activityUserPhone = $value;
                    break;
                case 'time_start_calendar_add_activity':
                    $this->activityTimeStart = $value;
                    break;
                case 'time_end_calendar_add_activity':
                    $this->activityTimeEnd = $value;
                    break;
                case 'calendar_status_add_activity':
                    if($value == CalendarStatus::CALENDAR_STATUS_ACCEPTED) {
                        $this->activityStatus = $value;
                    }
                    break;
            }
        }
    }
}