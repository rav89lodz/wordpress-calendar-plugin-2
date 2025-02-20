<?php

namespace CalendarPlugin\src\classes\models;

class MainSettingsModel extends Model
{
    public $oneDayView;
    public $horizontalCalendarGrid;
    public $cellMinHeight;
    public $addScrollToTable;
    public $gridWidth;
    public $gridHeight;
    public $makeRsvByCalendar;
    public $fluentCalendarGrid;
    public $durationTimeOnGrid;
    public $activityPlaceOnGrid;
    public $startTimeOnGrid;
    public $endTimeOnGrid;
    public $recipients;
    public $messageSuccess;
    public $messageError;
    public $reservationSendMessage;
    public $startAt;
    public $endAt;
    public $interval;
    public $limitOptions;
    public $removeAfterLimitReached;
    public $limitReachedColor;
    public $limitReachedColorValue;
    public $lastHourOnGrid;
    public $captchaSiteKey;
    public $captchaSecretKey;

    /**
     * Constructor
     * 
     * @param array data
     */
    public function __construct($data = null) {
        $this->oneDayView = false;
        $this->horizontalCalendarGrid = false;
        $this->cellMinHeight = null;
        $this->addScrollToTable = false;
        $this->gridWidth = null;
        $this->gridHeight = null;
        $this->makeRsvByCalendar = false;
        $this->fluentCalendarGrid = false;
        $this->durationTimeOnGrid = false;
        $this->activityPlaceOnGrid = false;
        $this->startTimeOnGrid = false;
        $this->endTimeOnGrid = false;
        $this->recipients = null;
        $this->messageSuccess = null;
        $this->messageError = null;
        $this->reservationSendMessage = null;
        $this->startAt = "00:00";
        $this->endAt = "00:00";
        $this->interval = 60;
        $this->limitOptions = "sended";
        $this->removeAfterLimitReached = false;
        $this->limitReachedColor = false;
        $this->limitReachedColorValue = null;
        $this->lastHourOnGrid = false;
        $this->captchaSiteKey = null;
        $this->captchaSecretKey = null;

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
                case 'calendar_plugin_one_day_view':
                    $this->oneDayView = $value;
                    break;
                case 'calendar_plugin_horizontal_calendar_grid':
                    $this->horizontalCalendarGrid = $value;
                    break;
                case 'calendar_plugin_cell_min_height':
                    $this->cellMinHeight = $value;
                    break;
                case 'calendar_plugin_add_scroll_to_table':
                    $this->addScrollToTable = $value;
                    break;
                case 'calendar_plugin_grid_width':
                    $this->gridWidth = $value;
                    break;
                case 'calendar_plugin_grid_height':
                    $this->gridHeight = $value;
                    break;
                case 'calendar_plugin_make_rsv_by_calendar':
                    $this->makeRsvByCalendar = $value;
                    break;
                case 'calendar_plugin_fluent_calendar_grid':
                    $this->fluentCalendarGrid = $value;
                    break;
                case 'calendar_plugin_duration_time_on_grid':
                    $this->durationTimeOnGrid = $value;
                    break;
                case 'calendar_plugin_activity_place_on_grid':
                    $this->activityPlaceOnGrid = $value;
                    break;
                case 'calendar_plugin_start_time_on_grid':
                    $this->startTimeOnGrid = $value;
                    break;
                case 'calendar_plugin_end_time_on_grid':
                    $this->endTimeOnGrid = $value;
                    break;
                case 'calendar_plugin_recipients':
                    $this->recipients = $value;
                    break;
                case 'calendar_plugin_message_success':
                    $this->messageSuccess = $value;
                    break;
                case 'calendar_plugin_message_error':
                    $this->messageError = $value;
                    break;    
                case 'calendar_plugin_reservation_send_message':
                    $this->reservationSendMessage = $value;
                    break;
                case 'calendar_plugin_start_at':
                    $this->startAt = $value;
                    break;
                case 'calendar_plugin_end_at':
                    $this->endAt = $value;
                    break;
                case 'calendar_plugin_interval':
                    $this->interval = $value;
                    break;
                case 'calendar_plugin_limit_options':
                    $this->limitOptions = $value;
                    break;
                case 'calendar_plugin_remove_after_limit_reached':
                    $this->removeAfterLimitReached = $value;
                    break;
                case 'calendar_plugin_limit_reached_color':
                    $this->limitReachedColor = $value;
                    break;
                case 'calendar_plugin_limit_reached_color_value':
                    $this->limitReachedColorValue = $value;
                    break;
                case 'calendar_plugin_last_hour_on_grid':
                    $this->lastHourOnGrid = $value;
                    break;
                case 'calendar_plugin_captcha_site_key':
                    $this->captchaSiteKey = $value;
                    break;
                case 'calendar_plugin_captcha_secret_key':
                    $this->captchaSecretKey = $value;
                    break;
            }
        }
    }
}