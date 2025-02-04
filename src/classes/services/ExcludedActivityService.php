<?php

namespace CalendarPlugin\src\classes\services;

use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\ExcludedActivityModel;

class ExcludedActivityService
{
    private $message;
    private $code;

    public function __construct($data = null) {
        if($data !== null) {
            if(isset($data['excluded_activity_id_update'])) {
                $id = intval($data['excluded_activity_id_update']);
                unset($data['excluded_activity_id_update']);
                $this->update_excluded_activity_data($id, $data);
            }
            else if(isset($data['excluded_activity_id_delete'])) {
                $id = intval($data['excluded_activity_id_delete']);
                $this->delete_excluded_activity_data($id);
            }
            else if(isset($data['activity_is_active']) && count($data) === 2) {
                $langService = new LanguageService('adminMenu');
                $result = $this->set_gird_element_visibility($data['id'], $data['activity_is_active']);
                if($result === true) {
                    $this->message = $langService->langData['update_success'];
                    $this->code = 200;
                }
                else {
                    $this->message = $langService->langData['update_failed'];
                    $this->code = 400;
                }
            }
            else {
                $this->save_excluded_activity_data($data);
            }
        }
    }

    /**
     * Get response message and code after save data
     * 
     * @return void
     */
    public function get_response_after_save() {
        return ["message" => $this->message, "code" => $this->code];
    }

    /**
     * Update calendar element visibility
     * 
     * @param int id
     * @param array data
     * @return bool
     */
    private function set_gird_element_visibility($id, $visibility) {
        $excludedActivity = new ExcludedActivityModel();
        $result = $excludedActivity->find($id);
        if($result === null) {
            return false;
        }
        $result->excludedIsActive = $visibility;
        unset($result->id);
        unset($result->calendar_key_type);
        unset($result->created_at);
        unset($result->deleted_at);
        $result = $excludedActivity->update($id, CalendarTypes::CALENDAR_EXCLUDED_ACTIVITY, $result);
        return boolval($result);
    }

    /**
     * Save excluded activity data
     * 
     * @param array data
     * @return bool
     */
    private function save_excluded_activity_data($data) {
        $excludedActivity = new ExcludedActivityModel($data);
        $result = $excludedActivity->create(CalendarTypes::CALENDAR_EXCLUDED_ACTIVITY, $excludedActivity);
        return boolval($result);
    }

    /**
     * Update excluded activity data
     * 
     * @param int id
     * @param array data
     * @return bool
     */
    private function update_excluded_activity_data($id, $data) {
        $excludedActivity = new ExcludedActivityModel($data);
        $result = $excludedActivity->find($id);
        if($result === null) {
            return false;
        }
        $result = $excludedActivity->update($id, CalendarTypes::CALENDAR_EXCLUDED_ACTIVITY, $excludedActivity);
        return boolval($result);
    }

    /**
     * Delete excluded activity data
     * 
     * @param int id
     * @return bool
     */
    private function delete_excluded_activity_data($id) {
        $excludedActivity = new ExcludedActivityModel();
        $result = $excludedActivity->find($id);
        if($result === null) {
            return false;
        }
        $result = $excludedActivity->delete($id);
        return boolval($result);
    }
}