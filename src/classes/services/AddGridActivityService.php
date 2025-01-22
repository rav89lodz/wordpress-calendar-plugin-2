<?php

namespace CalendarPlugin\src\classes\services;

use CalendarPlugin\src\classes\consts\CalendarStatus;
use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\AddGridActivityModel;
use CalendarPlugin\src\classes\Utils;

class AddGridActivityService
{
    private $utils;
    private $model;
    private $service;

    /**
     * Constructor
     * 
     * @param array|object|null data
     */
    public function __construct($data = null) {
        $this->service = new LanguageService(['addActivityMessage', 'addActivityFriendlyNames']);
        $this->utils = new Utils;

        $this->model = null;
        if($data !== null) {
            if(isset($data['new_grid_activity_id_delete'])) {
                $id = intval($data['new_grid_activity_id_delete']);
                $this->delete_add_grid_data($id);
            }
            else if(isset($data['new_grid_activity_id_update'])) {
                $id = intval($data['new_grid_activity_id_update']);
                $status = $data['calendar_status'];
                $this->change_status_for_new_grid_data($id, $status, $data);
            }
            else {
                $this->model = new AddGridActivityModel($data);
            }
        }
    }

    /**
     * Change status for new grid data -> accepted / rejected
     * 
     * @param int id
     * @param CalendarStatus status
     * @param array data
     * @return bool
     */
    private function change_status_for_new_grid_data($id, $status, $data) {
        $activity = new AddGridActivityModel();
        $result = $activity->find($id);
        if($result === null) {
            return false;
        }

        $result->activityStatus = $status;
        unset($result->id);
        unset($result->calendar_key_type);
        unset($result->created_at);
        unset($result->deleted_at);

        $result = $activity->update($id, CalendarTypes::CALENDAR_ADD_GRID_ACTIVITY, $result);
        if(boolval($result) === true && $status == CalendarStatus::CALENDAR_STATUS_ACCEPTED) {
            new GridPageService($data);
        }
        return boolval($result);
    }

    /**
     * Store activity in DB, send email and get response message
     * 
     * @return array
     */
    public function get_response_after_add_activity() {
        $message = $this->store_activity_data();
        if($message === null) {
            return $this->utils->set_success_error_message_with_code($this->model->activityUserName, 422);
        }

        $message = $this->utils->set_up_polish_characters($message);
        $subject = $this->service->langData['subject'];
        $replayTo = ["name" => $this->model->activityUserName, "email" => $this->model->activityUserEmail];

        $isSended = $this->utils->send_email_with_data($message, $subject, $replayTo);

        if($isSended === false) {
            return $this->utils->set_success_error_message_with_code($this->model->activityUserName, 422, 98);
        }
        return $this->utils->set_success_error_message_with_code($this->model->activityUserName, 200, 2);
    }

    /**
     * Add activity to DB by model data and get response message
     * 
     * @return string|null
     */
    private function store_activity_data() {
        $result = $this->insert_add_grid_data();
        if($result === false) {
            return null;
        }

        $message = "<h2>" . $this->service->langData['message_from'] . " {$this->model->activityUserName}</h2>";

        $message .= "<div><strong>" . $this->service->langData['message_beginning'] . $this->model->activityName .
                    "</strong></div><br><div><strong>" . $this->service->langData['add_activity_user_email'] . "</strong>: " . $this->model->activityUserEmail .
                    "</div><br><div><strong>" . $this->service->langData['add_activity_user_phone'] . "</strong>: " . $this->model->activityUserPhone .
                    "</div><br><div><strong>" . $this->service->langData['add_activity_date'] . "</strong>: " . $this->model->activityDate .
                    "</div><br><div><strong>" . $this->service->langData['add_activity_time_start'] . "</strong>: " . $this->model->activityTimeStart .
                    "</div><br><div><strong>" . $this->service->langData['add_activity_time_end'] . "</strong>: " . $this->model->activityTimeEnd . "</div>";

        return $message;
    }

    /**
     * Insert data to DB
     * 
     * @return bool
     */
    private function insert_add_grid_data() {
        if($this->model === null) {
            return false;
        }
        $result = $this->model->create(CalendarTypes::CALENDAR_ADD_GRID_ACTIVITY, $this->model);
        return boolval($result);
    }

    /**
     * Delete data
     * 
     * @param int id
     * @return bool
     */
    private function delete_add_grid_data($id) {
        $activity = new AddGridActivityModel();
        $result = $activity->find($id);
        if($result === null) {
            return false;
        }
        $result = $activity->delete($id);
        return boolval($result);
    }
}