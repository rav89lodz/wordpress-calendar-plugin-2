<?php

namespace CalendarPlugin\src\classes\models;

use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\services\LanguageService;

class CalendarModel extends Model
{
    private $currentDate;
    private $currentTime;
    private $currentMondayDate;
    private $firstHourOnCalendar;
    private $lastHourOnCalendar;
    private $currentMonthName;
    private $calendarInterval;
    private $calendarReservation;
    private $durationOnGrid;
    private $placeActivityOnGrid;
    private $startTimeOnGrid;
    private $endTimeOnGrid;
    private $fulentCalendarGrid;
    private $horizontalCalendarGrid;
    private $addScrollToTable;
    private $calendarGridWidth;
    private $calendarGridHeight;
    private $calendarCellMinHeight;
    private $calendarOneDayView;
    private $currentData;
    private $calendarCaptchaSiteKey;
    private $calendarCaptchaSecretKey;

    /**
     * Constructor
     * 
     * @param string monthNumber
     * @param string startDate
     */
    public function __construct($monthNumber = null, $startDate = null) {
        if($monthNumber !== null && $startDate === null) {
            $this->currentMondayDate = $this->get_first_monday($monthNumber);
        }
        else {
            $this->currentMondayDate = $this->set_current_monday_date_form_param($startDate);
        }

        if($monthNumber === null && $startDate !== null) {
            $this->currentMonthName = $this->set_current_month_name($startDate);
        }
        else {
            $this->currentMonthName = $this->set_current_month_name($monthNumber);
        }

        $this->currentData = $this->get(CalendarTypes::CALENDAR_OPTION);
        $this->currentDate = $this->set_current_date($startDate);
        $this->currentTime = $this->set_current_time();
        $this->firstHourOnCalendar = $this->set_first_hour_on_calendar();
        $this->lastHourOnCalendar = $this->set_last_hour_on_calendar();
        $this->calendarInterval = $this->set_calendar_interval_option();
        $this->calendarReservation = $this->set_calendar_option('makeRsvByCalendar');
        $this->durationOnGrid = $this->set_calendar_option('durationTimeOnGrid');
        $this->placeActivityOnGrid = $this->set_calendar_option('activityPlaceOnGrid');
        $this->startTimeOnGrid = $this->set_calendar_option('startTimeOnGrid');
        $this->endTimeOnGrid = $this->set_calendar_option('endTimeOnGrid');
        $this->fulentCalendarGrid = $this->set_calendar_option('fluentCalendarGrid');
        $this->horizontalCalendarGrid = $this->set_calendar_option('horizontalCalendarGrid');
        $this->addScrollToTable = $this->set_calendar_option('addScrollToTable');
        $this->calendarGridWidth = $this->set_calendar_grid_params('gridWidth');
        $this->calendarGridHeight = $this->set_calendar_grid_params('gridHeight');
        $this->calendarCellMinHeight = $this->set_calendar_grid_params('cellMinHeight');
        $this->calendarOneDayView = $this->set_calendar_option('oneDayView');
        $this->calendarCaptchaSiteKey = $this->set_captcha_details('captchaSiteKey');
        $this->calendarCaptchaSecretKey = $this->set_captcha_details('captchaSecretKey');
    }

    /**
     * Get currentDate
     * 
     * @return string|null
     */
    public function get_cuttent_date() {
        return $this->currentDate;
    }

    /**
     * Get currentTime
     * 
     * @return string|null
     */
    public function get_cuttent_time() {
        return $this->currentTime;
    }

    /**
     * Get currentMondayDate
     * 
     * @return string|null
     */
    public function get_cuttent_monday_date() {
        return $this->currentMondayDate;
    }

    /**
     * Get firstHourOnCalendar
     * 
     * @return string|null
     */
    public function get_first_hour_on_calendar() {
        return $this->firstHourOnCalendar;
    }

    /**
     * Get lastHourOnCalendar
     * 
     * @return string|null
     */
    public function get_last_hour_on_calendar() {
        return $this->lastHourOnCalendar;
    }

    /**
     * Get date in format Y-m-d with addition days
     * 
     * @return string
     */
    public function get_monday_plus_days($days) {
        return date('Y-m-d', strtotime($this->currentMondayDate . "+$days days"));
    }

    /**
     * Get currentMonthName
     * 
     * @return string|null
     */
    public function get_cuttent_month_name() {
        return $this->currentMonthName;
    }

    /**
     * Get calendarInterval
     * 
     * @return string|null
     */
    public function get_calendar_interval() {
        return $this->calendarInterval;
    }

    /**
     * Get calendarReservation
     * 
     * @return bool
     */
    public function get_calendar_reservation() {
        return $this->calendarReservation;
    }

    /**
     * Get durationOnGrid
     * 
     * @return bool
     */
    public function get_duration_on_grid() {
        return $this->durationOnGrid;
    }

    /**
     * Get placeActivityOnGrid
     * 
     * @return bool
     */
    public function get_place_activity_on_grid() {
        return $this->placeActivityOnGrid;
    }

    /**
     * Get startTimeOnGrid
     * 
     * @return bool
     */
    public function get_start_time_on_grid() {
        return $this->startTimeOnGrid;
    }

    /**
     * Get endTimeOnGrid
     * 
     * @return bool
     */
    public function get_end_time_on_grid() {
        return $this->endTimeOnGrid;
    }

    /**
     * Get fulentCalendarGrid
     * 
     * @return bool
     */
    public function get_fluent_calendar_grid() {
        return $this->fulentCalendarGrid;
    }

    /**
     * Get horizontalCalendarGrid
     * 
     * @return bool
     */
    public function get_horizontal_calendar_grid() {
        return $this->horizontalCalendarGrid;
    }

    /**
     * Get addScrollToTable
     * 
     * @return bool
     */
    public function get_add_scroll_to_table() {
        return $this->addScrollToTable;
    }

    /**
     * Get calendarGridWidth
     * 
     * @return string
     */
    public function get_calendar_grid_width() {
        return  $this->calendarGridWidth;
    }

    /**
     * Get calendarGridHeight
     * 
     * @return string
     */
    public function get_calendar_grid_height() {
        return  $this->calendarGridHeight;
    }

    /**
     * Get calendarCellMinHeight
     * 
     * @return string
     */
    public function get_calendar_cell_min_height() {
        return  $this->calendarCellMinHeight;
    }

    /**
     * Get calendarOneDayView
     * 
     * @return string
     */
    public function get_calendar_one_day_view() {
        return $this->calendarOneDayView;
    }

    /**
     * Get calendarCaptchaSiteKey
     * 
     * @return string
     */
    public function get_calendar_captcha_site_key() {
        return $this->calendarCaptchaSiteKey;
    }

    /**
     * Get calendarCaptchaSecretKey
     * 
     * @return string
     */
    public function get_calendar_captcha_secret_key() {
        return $this->calendarCaptchaSecretKey;
    }

    /**
     * Set current monday date from passing start date
     * 
     * @param string|null startDate
     * @return string
     */
    private function set_current_monday_date_form_param($startDate = null) {
        if($startDate == null) {
            return $this->set_current_monday_date();
        }

        $timestamp = strtotime($startDate);
        $dayOfWeek = date('N', $timestamp);
        $difference = ($dayOfWeek - 1) * 86400;
        $mondayTimestamp = $timestamp - $difference;
        return date('Y-m-d', $mondayTimestamp);
    }

    /**
     * Get the first monday date at the month from passing date
     * 
     * @param string dateString
     * @return string
     */
    private function get_first_monday($dateString) {
        list($year, $month, $day) = explode('-', $dateString);
        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $dayOfWeek = date('w', $firstDayOfMonth);
        $offset = ($dayOfWeek == 0) ? 1 : (8 - $dayOfWeek);
        $firstMonday = mktime(0, 0, 0, $month, 1 + $offset, $year);
        return date('Y-m-d', $firstMonday);
    }
    
    /**
     * Set claendar option by DB data
     * 
     * @param string option
     * @return bool
     */
    private function set_calendar_option($option) {
        if($this->currentData !== null) {
            return boolval($this->currentData->$option);
        }
        return false;
    }

    /**
     * Set claendar interval option from DB data
     * 
     * @return int
     */
    private function set_calendar_interval_option() {
        if($this->currentData !== null) {
            return intval($this->currentData->interval);
        }
        return 60;
    }

    /**
     * Set claendar grid params from DB data
     * 
     * @return string
     */
    private function set_calendar_grid_params($param) {
        if(($this->currentData === null || ! isset($this->currentData->$param)) && $param == "gridHeight") {
            return "100%";
        }
        if(($this->currentData === null || ! isset($this->currentData->$param)) && $param == "gridWidth") {
            return "1200px";
        }
        if(($this->currentData === null || ! isset($this->currentData->$param)) && $param == "cellMinHeight") {
            return 80;
        }
        return $this->currentData->$param;
    }

    /**
     * Set claendar captcha details
     * 
     * @return string
     */
    private function set_captcha_details($param) {
        if($this->currentData === null || ! isset($this->currentData->$param)) {
            return null;
        }
        return $this->currentData->$param;
    }

    /**
     * Set current date in format Y-m-d
     * 
     * @param string|null date
     * @return string
     */
    private function set_current_date($date) {
        if($date === null) {
            return date('Y-m-d');
        }
        return date('Y-m-d', strtotime($date));
    }

    /**
     * Set current time in format H:i:s
     * 
     * @return string
     */
    private function set_current_time() {
        return date('H:i:s');
    }

    /**
     * Set current monday date on this week
     * 
     * @return string
     */
    private function set_current_monday_date() {
        $monday = strtotime('next Monday -1 week');
        return date('w', $monday) == date('w') ? date('Y-m-d', strtotime(date("Y-m-d", $monday)." +7 days")) : date('Y-m-d', $monday);
    }

    /**
     * Set first hour on calendar
     * 
     * @return string
     */
    private function set_first_hour_on_calendar() {
        if($this->currentData !== null) {
            return $this->currentData->startAt;
        }
        return "05:00"; "05:00";
    }

    /**
     * Set last hour on calendar
     * 
     * @return string
     */
    private function set_last_hour_on_calendar() {
        if($this->currentData !== null) {
            return $this->currentData->endAt;
        }
        return "23:30";
    }

    /**
     * Set current month name
     * 
     * @param string|null date on month
     * @return string
     */
    private function set_current_month_name($month = null) {
        $service = new LanguageService('months');
        $months = $service->langData;
        if($month !== null) {
            return $months[strtolower(date('F', strtotime($month)))];
        }
        return $months[strtolower(date('F'))];
    }
}