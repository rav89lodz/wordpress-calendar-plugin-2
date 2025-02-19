<?php

/**
 * Plugin Name:       Calendar Plugin
 * Plugin URI:        https://github.com/rav89lodz/wordpress-calendar-plugin-2
 * Description:       Calendar Plugin for modern term reservation form
 * Version:           1.0.0
 * Requires at least: 6.6.2
 * Requires PHP:      7.2
 * Author:            Rafał Chęciński
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://github.com/rav89lodz/wordpress-calendar-plugin-2
 * Text Domain:       calendar-plugin
 * Domain Path:       /languages
 */

if (! defined('ABSPATH')) {
    exit;
}

if (! class_exists('CalendarPlugin')) {
    class CalendarPlugin
    {
        public function __construct() {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            
            $plugin_data = get_plugin_data( __FILE__ );

            define('CALENDAR_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
            define('CALENDAR_PLUGIN_URL', plugin_dir_url( __FILE__ ));
            define('CALENDAR_PLUGIN_VERSION', $plugin_data['Version']);
            define('CALENDAR_PLUGIN_PER_PAGE', 30);
        }

        public function initialize() {
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/CalendarDB.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/FormValidator.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/Utils.php');

            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/setup/CalendarPluginInstall.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/setup/CalendarPluginUninstall.php');

            include_once CALENDAR_PLUGIN_PATH . '/src/includes/calendar-setup.php';
            include_once CALENDAR_PLUGIN_PATH . '/src/includes/calendar-menu.php';
            include_once CALENDAR_PLUGIN_PATH . '/src/includes/utilities.php';

            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/AddGridActivityService.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/CalendarService.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/ExcludedActivityService.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/GridPageService.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/LanguageService.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/OptionsPageService.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/PaginationService.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/PlacesPageService.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/ReservationService.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/SearchService.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/SessionService.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/SortService.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/services/ValidationService.php');

            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/forms/CalendarForm.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/forms/CalendarHorizontalForm.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/forms/CalendarOneDayForm.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/forms/CalendarVerticalForm.php');

            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/models/Model.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/models/ActivityModel.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/models/AddGridActivityModel.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/models/CalendarModel.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/models/ExcludedActivityModel.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/models/MainSettingsModel.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/models/MessageModel.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/models/PlaceModel.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/models/ReservationModel.php');

            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/consts/CalendarTypes.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/consts/CalendarStatus.php');
            require_once(CALENDAR_PLUGIN_PATH . '/src/classes/consts/CalendarSort.php');
        }
    }

    $calendarPlugin = new CalendarPlugin;
    $calendarPlugin->initialize();

    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    add_filter( 'plugin_action_links', function( $actions, $plugin_file ) {
        static $plugin;

        if (!isset($plugin)) {
            $plugin = plugin_basename(__FILE__);
        }
        
        if ($plugin == $plugin_file) {
            $actions = array_merge(['settings' => '<a href="admin.php?page=calendar-plugin-main-menu">' . __('Settings', 'General') . '</a>'], $actions);
        }

        return $actions;
    }, 10, 5 );
}

/**
 * Die and dump
 * 
 * @param mixed data
 * @return void
 */
function dd(...$data) {
    foreach($data as $element) {
        // var_export($element, true);
        var_dump($element);
    }
    die();
}
