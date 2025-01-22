<?php

namespace CalendarPlugin\src\classes\setup;

use CalendarPlugin\src\classes\CalendarDB;

class CalendarPluginInstall
{
    private $DB;

    /**
     * Constructor
     */
    public function __construct() {
        $this->DB = new CalendarDB;

        if($this->is_installed() === false) {
            $this->create_calendar_plugin_table();
        }
    }

    /**
     * Check plugin is installed
     * 
     * @return bool
     */
    private function is_installed() {
        $version = get_option('calendar_plugin_version');
        if(isset($version) && $version === CALENDAR_PLUGIN_VERSION) {
            return true;
        }
        return false;
    }

    /**
     * Set plugin vervion on DB
     * 
     * @param string version
     * @return void
     */
    private function set_plugin_version($version) {
        update_option('calendar_plugin_version', $version);
    }

    /**
     * Create plugins table
     * 
     * @return void
     */
    private function create_calendar_plugin_table() {
        $this->DB->create_db_table();
        $this->set_plugin_version(CALENDAR_PLUGIN_VERSION);
    }
}