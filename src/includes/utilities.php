<?php

if (! defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', 'enqueue_custom_scripts_for_calendar_plugin');

add_action('admin_enqueue_scripts', 'enqueue_admin_scripts_for_calendar_plugin');

/**
 * Enqueue custom scripts
 * 
 * @return void
 */
function enqueue_custom_scripts_for_calendar_plugin() {
    wp_enqueue_style('calendar-form-style', CALENDAR_PLUGIN_URL . 'src/assets/css/bootstrap.min.css');
    wp_enqueue_style('calendar-form-style-map', CALENDAR_PLUGIN_URL . 'src/assets/css/bootstrap.min.css.map');
    wp_enqueue_script('calendar-form-style', CALENDAR_PLUGIN_URL . 'src/assets/js/bootstrap.min.js');
    // wp_enqueue_script('calendar-form-style-map', CALENDAR_PLUGIN_URL . 'src/assets/js/bootstrap.min.js.map');

    wp_enqueue_style('calendar-form-plugin', CALENDAR_PLUGIN_URL . 'src/assets/css/calendar-plugin.css');

    wp_enqueue_script('calendar-form-functions', CALENDAR_PLUGIN_URL . 'src/assets/js/calendar-functions.js', '', '', true);
    wp_enqueue_script('calendar-form-plugin', CALENDAR_PLUGIN_URL . 'src/assets/js/calendar-plugin.js', '', '', true);
    wp_enqueue_script('calendar-form-plugin-form', CALENDAR_PLUGIN_URL . 'src/assets/js/calendar-plugin-form.js', '', '', true);
}

/**
 * Enqueue custom scripts for admin page
 * 
 * @return void
 */
function enqueue_admin_scripts_for_calendar_plugin() {
    wp_enqueue_style('calendar-form-style', CALENDAR_PLUGIN_URL . 'src/assets/css/bootstrap.min.css');
    wp_enqueue_style('calendar-form-style-map', CALENDAR_PLUGIN_URL . 'src/assets/css/bootstrap.min.css.map');
    wp_enqueue_script('calendar-form-style', CALENDAR_PLUGIN_URL . 'src/assets/js/bootstrap.min.js');
    
    wp_enqueue_style('calendar-form-plugin-admin', CALENDAR_PLUGIN_URL . 'src/assets/css/calendar-plugin-admin.css');
    wp_enqueue_style('calendar-form-plugin-admin-icon', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
    
    wp_enqueue_script('calendar-form-plugin-admin', CALENDAR_PLUGIN_URL . 'src/assets/js/calendar-plugin-admin.js');
    wp_enqueue_script('calendar-form-plugin-admin-icon', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js');
}