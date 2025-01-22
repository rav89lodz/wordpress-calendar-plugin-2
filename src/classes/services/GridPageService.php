<?php

namespace CalendarPlugin\src\classes\services;

use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\ActivityModel;

class GridPageService
{
    private $message;
    private $code;
    private $langService;

    /**
     * Constructor
     */
    public function __construct($data = null) {
        if($data !== null) {
            if(isset($data['activity_grid_id_update'])) {
                $id = intval($data['activity_grid_id_update']);
                unset($data['activity_grid_id_update']);
                $this->update_plugin_grid_data($id, $data);
            }
            else if(isset($data['activity_grid_id_delete'])) {
                $id = intval($data['activity_grid_id_delete']);
                $this->delete_plugin_grid_data($id);
            }
            else if(isset($data['activity_is_active']) && count($data) === 2) {
                $this->langService = new LanguageService('adminMenu');
                $result = $this->set_gird_element_visibility($data['id'], $data['activity_is_active']);
                if($result === true) {
                    $this->message = $this->langService->langData['update_success'];
                    $this->code = 200;
                }
                else {
                    $this->message = $this->langService->langData['update_failed'];
                    $this->code = 400;
                }
            }
            else {
                $this->save_plugin_grid_data($data);
            }
        }
    }

    /**
     * Update calendar element visibility
     * 
     * @param int id
     * @param array data
     * @return bool
     */
    private function set_gird_element_visibility($id, $visibility) {
        $activity = new ActivityModel();
        $result = $activity->find($id);
        if($result === null) {
            return false;
        }
        $result->isActive = $visibility;
        unset($result->id);
        unset($result->calendar_key_type);
        unset($result->created_at);
        unset($result->deleted_at);
        $result = $activity->update($id, CalendarTypes::CALENDAR_DATA, $result);
        return boolval($result);
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
     * Save calendar grid data
     * 
     * @param array data
     * @return bool
     */
    private function save_plugin_grid_data($data) {
        $activity = new ActivityModel($data);
        $result = $activity->create(CalendarTypes::CALENDAR_DATA, $activity);
        return boolval($result);
    }

    /**
     * Update calendar grid data
     * 
     * @param int id
     * @param array data
     * @return bool
     */
    private function update_plugin_grid_data($id, $data) {        
        $activity = new ActivityModel($data);
        $result = $activity->find($id);
        if($result === null) {
            return false;
        }
        $result = $activity->update($id, CalendarTypes::CALENDAR_DATA, $activity);
        return boolval($result);
    }

    /**
     * Delete calendar grid data
     * 
     * @param int id
     * @return bool
     */
    private function delete_plugin_grid_data($id) {
        $activity = new ActivityModel();
        $result = $activity->find($id);
        if($result === null) {
            return false;
        }
        $result = $activity->delete($id);
        return boolval($result);
    }
}