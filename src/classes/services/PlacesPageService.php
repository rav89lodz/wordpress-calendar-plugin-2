<?php

namespace CalendarPlugin\src\classes\services;

use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\PlaceModel;

class PlacesPageService
{
    public $shortCode;

    /**
     * Constructor
     */
    public function __construct($data = null) {
        if($data !== null) {
            if(isset($data['activity_place_id_update'])) {
                $id = intval($data['activity_place_id_update']);
                unset($data['activity_place_id_update']);
                $this->update_plugin_place_data($id, $data);
            }
            else if(isset($data['activity_place_id_delete'])) {
                $id = intval($data['activity_place_id_delete']);
                $this->delete_plugin_place_data($id);
            }
            else {
                $this->save_plugin_place_data($data);
            }
        }
    }

    /**
     * Save place data
     * 
     * @param array data
     * @return bool
     */
    private function save_plugin_place_data($data) {
        $placeSettings = new PlaceModel($data);
        $result = $placeSettings->create(CalendarTypes::CALENDAR_PLACE, $placeSettings);
        $this->shortCode = $placeSettings->shortCode;
        return boolval($result);
    }

    /**
     * Update place data
     * 
     * @param int id
     * @param array data
     * @return bool
     */
    private function update_plugin_place_data($id, $data) {
        $placeSettings = new PlaceModel($data);
        $result = $placeSettings->find($id);
        if($result === null || ! isset($result->shortCode)) {
            return false;
        }
        $result = $placeSettings->update($id, CalendarTypes::CALENDAR_PLACE, $placeSettings);
        $this->shortCode = $placeSettings->shortCode;
        return boolval($result);
    }

    /**
     * Delete place data
     * 
     * @param int id
     * @return bool
     */
    private function delete_plugin_place_data($id) {
        $placeSettings = new PlaceModel();
        $result = $placeSettings->find($id);
        if($result === null || ! isset($result->shortCode)) {
            return false;
        }
        $result = $placeSettings->delete($id);
        return boolval($result);
    }
}