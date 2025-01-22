<?php

namespace CalendarPlugin\src\classes\setup;

use CalendarPlugin\src\classes\CalendarDB;

class CalendarPluginUninstall
{
    private $DB;

    /**
     * Constructor
     */
    public function __construct() {
        $this->DB = new CalendarDB;
        $this->execute_uninstall_calendar_plugin();
    }

    /**
     * Execute uninstall plugin
     * 
     * @return void
     */
    private function execute_uninstall_calendar_plugin() {
        if(!defined('WP_UNINSTALL_PLUGIN')) {
            exit();
        }

        delete_option('calendar_plugin_version');
        $this->DB->drop_db_table();
    }
}