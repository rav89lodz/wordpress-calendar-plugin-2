<?php

namespace CalendarPlugin\src\classes\models;

use CalendarPlugin\src\classes\consts\CalendarStatus;
use CalendarPlugin\src\classes\FormValidator;

class ReservationModel extends Model
{
    public $userName;
    public $userEmail;
    public $reservationDate;
    public $reservationTime;
    public $activity;
    public $reservationStatus;

    /**
     * Constructor
     * 
     * @param array|object data
     */
    public function __construct($data = null) {
        $this->userName = null;
        $this->userEmail = null;
        $this->reservationDate = null;
        $this->reservationTime = null;
        $this->activity = null;
        $this->reservationStatus = CalendarStatus::CALENDAR_STATUS_REJECTED;

        if($data !== null && is_array($data)) {
            $this->set_data($data);
        }
    }

    /**
     * Validation for model data and set this data to properties
     * 
     * @param array|object data
     * @return void
     */
    private function set_data($data) {
        $validator = new FormValidator;
        foreach($data as $key => $value) {
            switch($key) {
                case 'user_name_calendar_modal':
                    $this->userName = $validator->validation_sequence_for_name($value);
                    break;
                case 'calendar_modal_day_name':
                    $this->reservationDate = $validator->validation_sequence_for_date($value);
                    break;
                case 'calendar_modal_hour':
                    $this->reservationTime = $validator->validation_sequence_for_time($value);
                    break;
                case 'user_email_calendar_modal':
                    $this->userEmail = $validator->validation_sequence_for_email($value);
                    break;
                case 'calendar_modal_hidden_id':
                    $id = $validator->is_valid_number($value) ? $value : 0;
                    $model = new ActivityModel();
                    $this->activity = $model->find($id);
                    break;
                case 'calendar_status_add_activity':
                    if($value == CalendarStatus::CALENDAR_STATUS_ACCEPTED) {
                        $this->reservationStatus = $value;
                    }
                    break;
            }
        }
    }
}