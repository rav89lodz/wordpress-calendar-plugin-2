<?php

namespace CalendarPlugin\src\classes\services;

use CalendarPlugin\src\classes\consts\CalendarStatus;
use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\ReservationModel;
use CalendarPlugin\src\classes\Utils;

class ReservationService
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
        $this->utils = new Utils;
        $this->service = new LanguageService(['reservationMessage', 'reservationFriendlyNames']);
        $this->model = new ReservationModel();
        if($data !== null) {
            if(isset($data['users_reservation_activity_id_delete'])) {
                $id = intval($data['users_reservation_activity_id_delete']);
                $this->delete_users_reservation_data($id);
            }
            else if(isset($data['users_reservation_activity_id_update'])) {
                $id = intval($data['users_reservation_activity_id_update']);
                $status = $data['calendar_status'];
                $this->change_status_for_users_reservation_data($id, $status, $data);
            }
            else {
                $this->model = new ReservationModel($data);
            }
        }
    }

    /**
     * Store activity in DB, send email and get response message
     * 
     * @return array
     */
    public function get_response_after_reservation() {
        $message = $this->store_reservation_data($this->model);
        $message[0] = $this->utils->set_up_polish_characters($message[0]);
        $subject = $this->service->langData['subject'];
        $replayTo = ["name" => $this->model->userName, "email" => $this->model->userEmail];

        $isSended = $this->utils->send_email_with_data($message[0], $subject, $replayTo);

        if($message[1] === false) {
            return $this->utils->set_success_error_message_with_code($this->model->userName, 422);
        }
        if($isSended === false) {
            return $this->utils->set_success_error_message_with_code($this->model->userName, 422, 98);
        }
        return $this->utils->set_success_error_message_with_code($this->model->userName, 200, 1);
    }

    /**
     * Check reservation limit by data
     * 
     * @param string activityId
     * @param string date
     * @return int
     */
    public function check_reservation_limit($activityId, $date) {                                                    
        $data = $this->model->all(CalendarTypes::CALENDAR_NEW_RESERVATION);
        $currentlimit = 0;
        foreach($data as $element) {
            if($element->activity !== null && $element->activity->id == $activityId && $element->reservationDate == $date) {
                $currentlimit ++;
            }
        }
        return $currentlimit;
    }

    /**
     * Add reservation to DB by model data and get response message
     * 
     * @return string|null
     */
    private function store_reservation_data() {
        $message = "<h2>" . $this->service->langData['message_from'] . " {$this->model->userName}</h2>";

        $activity = $this->model->activity;

        $limit = $this->check_reservation_limit($activity->id, $this->model->reservationDate);
        if($limit >= $activity->slot || $this->model->userName === null || $this->model->userEmail === null) {
            $message .= "<div><strong style='color:red'>" . $this->service->langData['message_beginning_failure'] .
                        "</strong></div><br><div><strong>" . $this->service->langData['user_email'] . "</strong>: " . $this->model->userEmail .
                        "</div><br><div><strong>" . $this->service->langData['reservation_date'] . "</strong>: " . $this->model->reservationDate .
                        "</div><br><div><strong>" . $this->service->langData['reservation_time'] . "</strong>: " . $this->model->reservationTime .
                        "</div><br><div><strong>" . $this->service->langData['activity_name'] . "</strong>: " . $activity->name . "</div>";
            return [$message, false];
        }

        $result = $this->insert_users_reservation_data();
        if($result === false) {
            return null;
        }

        $message .= "<div><strong style='color:green'>" . $this->service->langData['message_beginning_success'] .
                    "</strong></div><br><div><strong>" . $this->service->langData['user_email'] . "</strong>: " . $this->model->userEmail .
                    "</div><br><div><strong>" . $this->service->langData['reservation_date'] . "</strong>: " . $this->model->reservationDate .
                    "</div><br><div><strong>" . $this->service->langData['reservation_time'] . "</strong>: " . $this->model->reservationTime .
                    "</div><br><div><strong>" . $this->service->langData['activity_name'] . "</strong>: " . $activity->name . "</div>";

        return [$message, true];
    }

    /**
     * Change status for users reservation data -> accepted / rejected
     * 
     * @param int id
     * @param CalendarStatus status
     * @param array data
     * @return bool
     */
    private function change_status_for_users_reservation_data($id, $status, $data) {
        $result = $this->model->find($id);
        if($result === null) {
            return false;
        }

        $result->reservationStatus = $status;
        unset($result->id);
        unset($result->calendar_key_type);
        unset($result->created_at);
        unset($result->deleted_at);

        $result = $this->model->update($id, CalendarTypes::CALENDAR_NEW_RESERVATION, $result);
        return boolval($result);
    }

    /**
     * Insert data to DB
     * 
     * @return bool
     */
    private function insert_users_reservation_data() {
        if($this->model === null) {
            return false;
        }
        $result = $this->model->create(CalendarTypes::CALENDAR_NEW_RESERVATION, $this->model);
        return boolval($result);
    }

    /**
     * Delete data
     * 
     * @param int id
     * @return bool
     */
    private function delete_users_reservation_data($id) {
        $reservation = new ReservationModel();
        $result = $reservation->find($id);
        if($result === null) {
            return false;
        }
        $result = $reservation->delete($id);
        return boolval($result);
    }
}