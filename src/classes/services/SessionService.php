<?php

namespace CalendarPlugin\src\classes\services;

class SessionService
{
    /**
     * Constructor
     */
    public function __construct() {
    }

    /**
     * Clear all sessions
     * 
     * @return void
     */
    public static function destroyAllCalendarPluginSession() {
        self::destroyExcludedFormSession();
        self::destroyCalendarGridFormSession();
        self::destroyActivityReservationSession();
        self::destroyGridReservationSession();
        self::destroyActivityPlacesSession();
    }

    /**
     * Clear all sessions except Calendar Grid Form
     * 
     * @return void
     */
    public static function destroySessionSequenceForCalendarGridForm() {
        self::destroyExcludedFormSession();
        self::destroyActivityReservationSession();
        self::destroyGridReservationSession();
        self::destroyActivityPlacesSession();
    }

    /**
     * Clear all sessions except Excluded Form
     * 
     * @return void
     */
    public static function destroySessionSequenceForExcludedForm() {
        self::destroyCalendarGridFormSession();
        self::destroyActivityReservationSession();
        self::destroyGridReservationSession();
        self::destroyActivityPlacesSession();
    }

    /**
     * Clear all sessions except Activity Reservation Form
     * 
     * @return void
     */
    public static function destroySessionSequenceForActivityReservation() {
        self::destroyExcludedFormSession();
        self::destroyCalendarGridFormSession();
        self::destroyGridReservationSession();
        self::destroyActivityPlacesSession();
    }

    /**
     * Clear all sessions except Grid Reservation Form
     * 
     * @return void
     */
    public static function destroySessionSequenceForGridReservation() {
        self::destroyExcludedFormSession();
        self::destroyCalendarGridFormSession();
        self::destroyActivityReservationSession();
        self::destroyActivityPlacesSession();
    }

    /**
     * Clear all sessions except Activity Places Form
     * 
     * @return void
     */
    public static function destroySessionSequenceForActivityPlaces() {
        self::destroyExcludedFormSession();
        self::destroyCalendarGridFormSession();
        self::destroyActivityReservationSession();
        self::destroyGridReservationSession();
    }

    /**
     * Destroy session for Excluded Form
     * 
     * @return void
     */
    public static function destroyExcludedFormSession() {
        unset($_SESSION['excluded_order_vector']);
        unset($_SESSION['excluded_order_by']);

        unset($_SESSION['search_bar_input_excluded']);
        unset($_SESSION['search_bar_option_excluded']);
        unset($_SESSION['search_bar_field_excluded']);
    }

    /**
     * Destroy session for Calendar Grid Form
     * 
     * @return void
     */
    public static function destroyCalendarGridFormSession() {
        unset($_SESSION['activity_order_vector']);
        unset($_SESSION['activity_order_by']);

        unset($_SESSION['search_bar_input_activity']);
        unset($_SESSION['search_bar_option_activity']);
        unset($_SESSION['search_bar_field_activity']);
    }

    /**
     * Destroy session for Activity Reservation Form
     * 
     * @return void
     */
    public static function destroyActivityReservationSession() {
        unset($_SESSION['activity_reservation_order_vector']);
        unset($_SESSION['activity_reservation_order_by']);

        unset($_SESSION['search_bar_input_activity_reservation']);
        unset($_SESSION['search_bar_option_activity_reservation']);
        unset($_SESSION['search_bar_field_activity_reservation']);
    }

    /**
     * Destroy session for Grid Reservation Form
     * 
     * @return void
     */
    public static function destroyGridReservationSession() {
        unset($_SESSION['reservation_order_vector']);
        unset($_SESSION['reservation_order_by']);

        unset($_SESSION['search_bar_input_reservation']);
        unset($_SESSION['search_bar_option_reservation']);
        unset($_SESSION['search_bar_field_reservation']);
    }

    /**
     * Destroy session for Activity Places Form
     * 
     * @return void
     */
    public static function destroyActivityPlacesSession() {
        unset($_SESSION['activity_place_order_vector']);
        unset($_SESSION['activity_place_order_by']);

        unset($_SESSION['search_bar_input_activity_place']);
        unset($_SESSION['search_bar_option_activity_place']);
        unset($_SESSION['search_bar_field_activity_place']);
    }
}