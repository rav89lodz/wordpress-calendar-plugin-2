<?php

namespace CalendarPlugin\src\classes\models;

use CalendarPlugin\src\classes\CalendarDB;

class Model
{
    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     * Handle DB result
     *
     * @param mixed result
     * @return object|array|null
     */
    private function handle_result($result) {
        if($result === null || $result === false) {
            return null;
        }
        if(is_array($result)) {
            $toReturn = [];
            foreach($result as $element) {
                $object = json_decode($element->calendar_value);
                unset($element->calendar_value);
                $toReturn[] = (object) ((array)$element + (array)$object);
            }
            return $toReturn;
        }
        else {
            $object = json_decode($result->calendar_value);
            unset($result->calendar_value);
            return (object) ((array)$result + (array)$object);
        }
    }

    /**
     * Get all data by type
     * 
     * @param string type
     * @param bool desc
     * @return object|null
     */
    public function all($type, $desc = false) {
        if($desc === false) {
            $result = CalendarDB::get_all_data_by_type($type);
            return $this->handle_result($result);
        }
        $result = CalendarDB::get_all_data_by_type($type, false, true);
        return $this->handle_result($result);
    }

    /**
     * Get all data by type with trashed records
     * 
     * @param string type
     * @param bool desc
     * @return object|null
     */
    public function all_trashed($type, $desc = false) {
        if($desc === false) {
            $result = CalendarDB::get_all_data_by_type($type, true);
            return $this->handle_result($result);
        }
        $result = CalendarDB::get_all_data_by_type($type, true, true);
        return $this->handle_result($result);
    }

    /**
     * Get data by type
     * 
     * @param string type
     * @return object|null
     */
    public function get($type) {
        $result = CalendarDB::get_data_by_type($type);
        return $this->handle_result($result);
    }

    /**
     * Get data by type with trashed records
     * 
     * @param string type
     * @return object|null
     */
    public function get_trashed($type) {
        $result = CalendarDB::get_data_by_type($type, true);
        return $this->handle_result($result);
    }

    /**
     * Find data by id
     * 
     * @param int id
     * @return object|null
     */
    public function find($id) {
        $result = CalendarDB::get_data_by_id($id);
        return $this->handle_result($result);
    }

    /**
     * Find data by id with trashed records
     * 
     * @param int id
     * @return object|null
     */
    public function find_trashed($id) {
        $result = CalendarDB::get_data_by_id($id, true);
        return $this->handle_result($result);
    }

    /**
     * Insert data
     * 
     * @param string type
     * @param mixed data
     * @return int|bool
     */
    public function create($type, $data) {
        return CalendarDB::insert_data($type, json_encode($data));
    }

    /**
     * Update data
     * 
     * @param int id
     * @param string type
     * @param mixed data
     * @return int|bool
     */
    public function update($id, $type, $data) {
        return CalendarDB::update_data($id, $type, json_encode($data));
    }

    /**
     * Soft delete data
     * 
     * @param int id
     * @return int|bool
     */
    public function delete($id) {
        return CalendarDB::soft_delete_data($id);
    }

    /**
     * Permanent delete data
     * 
     * @param int id
     * @return int|bool
     */
    public function force_delete($id) {
        return CalendarDB::delete_data($id);
    }
}