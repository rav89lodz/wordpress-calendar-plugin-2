<?php

namespace CalendarPlugin\src\classes\models;

use CalendarPlugin\src\classes\Utils;

class PlaceModel extends Model
{
    public $name;
    public $shortCode;

    /**
     * Constructor
     * 
     * @param array data
     */
    public function __construct($data = null) {
        $this->name = null;
        $this->shortCode = null;

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
                case 'activity_place_description':
                    $this->name = $value;
                    $this->shortCode = $this->create_short_code($value);
                    break;
            }
        }
    }

    /**
     * Create short code
     * 
     * @param string code
     * @return string
     */
    private function create_short_code($code) {
        $utils = new Utils;
        $short = $utils->remove_polish_letters($code);
        return strtolower(str_replace(" ", "_", $short));
    }
}