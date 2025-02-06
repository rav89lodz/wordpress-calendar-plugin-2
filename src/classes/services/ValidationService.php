<?php

namespace CalendarPlugin\src\classes\services;

use CalendarPlugin\src\classes\FormValidator;
use CalendarPlugin\src\classes\models\CalendarModel;

class ValidationService
{
    private $validator;
    private $model;

    /**
     * Constructor
     */
    public function __construct() {
        $this->validator = new FormValidator;
        $this->model = new CalendarModel();
    }

    /**
     * Validate data
     * 
     * @param array|object data
     * @return array
     */
    public function validate_data($data) {
        $toReturn = [];
        if(isset($_POST['user_name_calendar_modal']) || isset($_POST['user_name_calendar_add_activity'])) {
            if($this->model->get_calendar_captcha_secret_key() !== null && ! empty($this->model->get_calendar_captcha_secret_key()) && $this->recaptcha_verification() === false) {
                return $toReturn;
            }
        }

        foreach($data as $key => $value) {
            $toReturn[$key] = $this->check_data($key, $value);
        }

        return $toReturn;
    }

    /**
     * Recaptcha v3 verification
     * 
     * @return bool
     */
    private function recaptcha_verification() {
        $responseToken = null;
        if(isset($_POST['recaptcha_response'])) {
            $responseToken = $_POST['recaptcha_response'];
        }

        if(isset($_POST['recaptcha_response_contact'])) {
            $responseToken = $_POST['recaptcha_response_contact'];
        }

        if($responseToken !== null) {
            $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $this->model->get_calendar_captcha_secret_key() . '&response=' . $responseToken);
            $recaptcha = json_decode($recaptcha);
    
            if($recaptcha->success == true && $recaptcha->score >= 0.5 && $recaptcha->action == "contact") {
               return true;
            }
        }

        return false;
    }

    /**
     * Check data by field type
     * 
     * @param string key
     * @param mixed value
     * @return mixed
     */
    private function check_data($key, $value) {
        switch($key) {
            case 'calendar_plugin_one_day_view':
            case 'calendar_plugin_horizontal_calendar_grid':
            case 'calendar_plugin_add_scroll_to_table':
            case 'calendar_plugin_make_rsv_by_calendar':
            case 'calendar_plugin_fluent_calendar_grid':
            case 'calendar_plugin_duration_time_on_grid':
            case 'calendar_plugin_activity_place_on_grid':
            case 'calendar_plugin_start_time_on_grid':
            case 'calendar_plugin_end_time_on_grid':
            case 'activity_cyclic':
            case 'activity_is_active':
            case 'excluded_activity_is_active':
                return $this->validator->validation_sequence_for_checkbox($value);
            case 'calendar_plugin_cell_min_height':
            case 'calendar_plugin_grid_width':
            case 'calendar_plugin_grid_height':
            case 'calendar_plugin_interval':
            case 'activity_type':
            case 'excluded_activity_type':
            case 'activity_slot':
            case 'calendar_modal_hidden_id':
            case 'activity_place_id_update':
            case 'activity_place_id_delete':
            case 'activity_grid_id_update':
            case 'activity_grid_id_delete':
            case 'new_grid_activity_id_delete':
            case 'new_grid_activity_id_update':
            case 'users_reservation_activity_id_delete':
                if($this->validator->is_valid_number($value)) {
                    return $value;
                }
                return null;
            case 'activity_day':
                return $this->validator->validation_sequence_for_array($value);
            case 'calendar_plugin_recipients':
            case 'user_email_calendar_add_activity':
            case 'user_email_calendar_modal':
                return $this->validator->validation_sequence_for_email($value);
            case 'user_phone_calendar_add_activity':
                if($this->validator->is_valid_phone_number($value)) {
                    return $this->validator->sanitize_string($value);
                }
                return null;
            case 'calendar_plugin_message_success':
            case 'calendar_plugin_message_error':
            case 'calendar_plugin_reservation_send_message':
            case 'date_calendar_add_activity':
            case 'calendar_plugin_captcha_secret_key':
            case 'calendar_plugin_captcha_site_key':
            case 'activity_exclusion_days':
                return $this->validator->validation_sequence_for_text($value);
            case 'calendar_plugin_start_at':
            case 'calendar_plugin_end_at':
                if($this->validator->is_valid_select_field($value)) {
                    return $this->validator->validation_sequence_for_name($value);
                }
                return "00:00";
            case 'activity_start_at':
            case 'activity_end_at':
            case 'time_start_calendar_add_activity':
            case 'time_end_calendar_add_activity':
            case 'calendar_modal_hour':
                return $this->validator->validation_sequence_for_time($value);
            case 'excluded_activity_start_at':
            case 'excluded_activity_end_at':
                return $this->validator->validation_sequence_for_time_with_null($value);
            case 'activity_date':
            case 'excluded_activity_date':
            case 'calendar_modal_day_name':
            case 'activity_day_start_date':
            case 'activity_day_end_date':
                return $this->validator->validation_sequence_for_date($value);
            case 'activity_bg_color':
            case 'excluded_activity_bg_color':
                if($this->validator->is_valid_hex_color($value)) {
                    return $value;
                }
                return "#ff0000";
            case 'activity_place_description':
            case 'activity_name':
            case 'excluded_activity_name':
            case 'user_name_calendar_add_activity':
            case 'name_calendar_add_activity':
            case 'user_name_calendar_modal':
            default:
                return $this->validator->validation_sequence_for_name($value);
        }
    }
}