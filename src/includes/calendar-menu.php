<?php

if (! defined('ABSPATH')) {
    exit;
}

use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\PlaceModel;
use CalendarPlugin\src\classes\services\AddGridActivityService;
use CalendarPlugin\src\classes\services\GridPageService;
use CalendarPlugin\src\classes\services\LanguageService;
use CalendarPlugin\src\classes\services\OptionsPageService;
use CalendarPlugin\src\classes\services\ReservationService;
use CalendarPlugin\src\classes\services\ValidationService;
use CalendarPlugin\src\classes\Utils;

add_shortcode('calendar-grid1', 'show_calendar_grid');
add_shortcode('contact-form-calendar1', 'show_calendar_contact_form');

/**
 * Show calendar grid
 * 
 * @param mixed post_id
 * @return string|false
 */
function show_calendar_grid($post_id = null) {
    $post_content = get_post_field('post_content', $post_id);

    $utils = new Utils;
    $_POST['calendar_grid_short_code'] = $utils->prepare_current_short_codes($post_content);

    ob_start();
        include( CALENDAR_PLUGIN_PATH . '/src/templates/calendar-form.php' );
    return ob_get_clean();
}

/**
 * Show calendar contact form
 * 
 * @return string|false
 */
function show_calendar_contact_form() {
    ob_start();
        include( CALENDAR_PLUGIN_PATH . '/src/templates/calendar-contact-form.php' );
    return ob_get_clean();
}

/**
 * Render main admin menu
 * 
 * @return void
 */
function calendar_plugin_display_my_menu() {
    include( CALENDAR_PLUGIN_PATH . '/src/templates/main-menu-form.php' );
}

/**
 * Render submenu place
 * 
 * @return void
 */
function calendar_plugin_display_my_submenu_place() {
    include( CALENDAR_PLUGIN_PATH . '/src/templates/submenu-place-form.php' );
}

/**
 * Render submenu grid
 * 
 * @return void
 */
function calendar_plugin_display_my_submenu_grid() {
    include( CALENDAR_PLUGIN_PATH . '/src/templates/submenu-grid-form.php' );
}

/**
 * Render submenu add activity
 * 
 * @return void
 */
function calendar_plugin_display_my_submenu_grid_reservation() {
    include( CALENDAR_PLUGIN_PATH . '/src/templates/submenu-grid-reservation.php' );
}

/**
 * Render submenu reservation
 * 
 * @return void
 */
function calendar_plugin_display_my_submenu_activity_reservation() {
    include( CALENDAR_PLUGIN_PATH . '/src/templates/submenu-activity-reservation.php' );
}

/**
 * Create plugin menu in WP admin panel
 * 
 * @return void
 */
function calendar_plugin_wp_admin_menu() {
    $langService = new LanguageService(['optionPage', 'addActivityMenu', 'reservationMenu']);

    add_menu_page(
        $langService->langData['main_menu_settings'],
        $langService->langData['main_menu_settings'],
        'manage_options',
        'calendar-plugin-main-menu',
        'calendar_plugin_display_my_menu',
        'dashicons-calendar-alt',
        87
    );

    add_submenu_page(
        'calendar-plugin-main-menu',
        $langService->langData['activity_place_title'],
        $langService->langData['activity_place_title'],
        'manage_options',
        'calendar-plugin-submenu-place',
        'calendar_plugin_display_my_submenu_place'
    );

    add_submenu_page(
        'calendar-plugin-main-menu',
        $langService->langData['calendar_grid_data_title'],
        $langService->langData['calendar_grid_data_title'],
        'manage_options',
        'calendar-plugin-submenu-grid',
        'calendar_plugin_display_my_submenu_grid'
    );

    add_submenu_page(
        'calendar-plugin-main-menu',
        $langService->langData['activity_menu_description'],
        $langService->langData['activity_menu_description'],
        'manage_options',
        'calendar-plugin-submenu-grid-reservation',
        'calendar_plugin_display_my_submenu_grid_reservation'
    );

    add_submenu_page(
        'calendar-plugin-main-menu',
        $langService->langData['reservation_menu_description'],
        $langService->langData['reservation_menu_description'],
        'manage_options',
        'calendar-plugin-submenu-activity-reservation',
        'calendar_plugin_display_my_submenu_activity_reservation'
    );
}

add_action('admin_menu', 'calendar_plugin_wp_admin_menu');

add_action('rest_api_init', function() {
    register_rest_route('v1/calendar-admin-menu', 'main-form', [
        'methods' => 'POST',
        'callback' => 'handle_calendar_main_menu_form',
        'permission_callback' => [],
    ]);
    register_rest_route('v1/calendar-admin-menu', 'grid-form', [
        'methods' => 'POST',
        'callback' => 'handle_calendar_grid_menu_form',
        'permission_callback' => [],
    ]);
    register_rest_route('v1/calendar-grid-change', 'day', [
        'methods' => 'POST',
        'callback' => 'handle_calendar_grid_change_day',
        'permission_callback' => [],
    ]);
    register_rest_route('v1/calendar-grid-change', 'week', [
        'methods' => 'POST',
        'callback' => 'handle_calendar_grid_change_week',
        'permission_callback' => [],
    ]);
    register_rest_route('v1/calendar-grid-change', 'month', [
        'methods' => 'POST',
        'callback' => 'handle_calendar_grid_change_month',
        'permission_callback' => [],
    ]);
    register_rest_route('v1/calendar-grid-form', 'registration-for-activity', [
        'methods' => 'POST',
        'callback' => 'handle_calendar_grid_form_registration_for_activity',
        'permission_callback' => [],
    ]);
    register_rest_route('v1/calendar-grid-form', 'add-grid-activity', [
        'methods' => 'POST',
        'callback' => 'handle_calendar_form_grid_reservation',
        'permission_callback' => [],
    ]);
});

add_action('init', function() {
    $places = new PlaceModel();
    $places = $places->all(CalendarTypes::CALENDAR_PLACE, true);
    
    foreach($places as $place) {
        add_shortcode($place->shortCode, 'show_calendar_grid');
    }
});

/**
 * Handle rest API endpoint
 * 
 * @param mixed data
 * @return object|null
 */
function handle_calendar_main_menu_form($data) {
    $validationService = new ValidationService;
    $data = $validationService->validate_data(json_decode($data->get_body()));

    $service = new OptionsPageService($data);
    $response = $service->get_response_after_save();

    return new WP_Rest_Response($response['message'], $response['code']);
}

/**
 * Handle rest API endpoint
 * 
 * @param mixed data
 * @return object|null
 */
function handle_calendar_grid_menu_form($data) {
    $validationService = new ValidationService;
    $data = $validationService->validate_data(json_decode($data->get_body()));

    $service = new GridPageService($data);
    $response = $service->get_response_after_save();

    return new WP_Rest_Response($response['message'], $response['code']);
}

/**
 * Handle rest API endpoint
 * 
 * @param mixed data
 * @return string|false
 */
function handle_calendar_grid_change_day($data) {
    $data = json_decode($data->get_body());
    $_POST['calendar_grid_change_date'] = $data->data;
    $utils = new Utils;
    $_POST['calendar_grid_short_code'] = $utils->prepare_current_short_codes("[" . $data->short_code . "]");
   
    ob_start();
        include( CALENDAR_PLUGIN_PATH . '/src/templates/calendar-form.php' );
    return ob_get_clean();
}

/**
 * Handle rest API endpoint
 * 
 * @param mixed data
 * @return string|false
 */
function handle_calendar_grid_change_week($data) {
    $data = json_decode($data->get_body());
    $_POST['calendar_grid_change_date'] = $data->data;
    $utils = new Utils;
    $_POST['calendar_grid_short_code'] = $utils->prepare_current_short_codes("[" . $data->short_code . "]");
   
    ob_start();
        include( CALENDAR_PLUGIN_PATH . '/src/templates/calendar-form.php' );
    return ob_get_clean();
}

/**
 * Handle rest API endpoint
 * 
 * @param mixed data
 * @return string|false
 */
function handle_calendar_grid_change_month($data) {
    $data = json_decode($data->get_body());
    $_POST['calendar_grid_change_month'] = $data->data;
    $utils = new Utils;
    $_POST['calendar_grid_short_code'] = $utils->prepare_current_short_codes("[" . $data->short_code . "]");
    
    ob_start();
        include( CALENDAR_PLUGIN_PATH . '/src/templates/calendar-form.php' );
    return ob_get_clean();
}

/**
 * Handle rest API endpoint
 * 
 * @param mixed data
 * @return object|null
 */
function handle_calendar_grid_form_registration_for_activity($data) {
    $validationService = new ValidationService;
    $data = $validationService->validate_data(json_decode($data->get_body()));

    $service = new ReservationService($data);
    $response = $service->get_response_after_reservation();

    return new WP_Rest_Response($response['message'], $response['code']);
}

/**
 * Handle rest API endpoint
 * 
 * @param mixed data
 * @return object|null
 */
function handle_calendar_form_grid_reservation($data) {
    $validationService = new ValidationService;
    $data = $validationService->validate_data(json_decode($data->get_body()));

    $service = new AddGridActivityService($data);
    $response = $service->get_response_after_add_activity();

    return new WP_Rest_Response($response['message'], $response['code']);
}