<?php

use CalendarPlugin\src\classes\setup\CalendarPluginInstall;
use CalendarPlugin\src\classes\setup\CalendarPluginUninstall;

if (! defined('ABSPATH')) {
    exit;
}

add_action('init', 'install_calendar_plugin');

/**
 * Install plugin
 * 
 * @return CalendarPluginInstall
 */
function install_calendar_plugin() {
    register_uninstall_hook(CALENDAR_PLUGIN_PATH, 'uninstall_calendar_plugin');
    // register_deactivation_hook(CALENDAR_PLUGIN_PATH, 'uninstall_calendar_plugin');
    return new CalendarPluginInstall;
}

/**
 * Uninstall plugin
 * 
 * @return CalendarPluginUninstall
 */
function uninstall_calendar_plugin() {
    return new CalendarPluginUninstall;
}
