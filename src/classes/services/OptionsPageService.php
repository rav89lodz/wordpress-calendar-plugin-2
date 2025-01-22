<?php

namespace CalendarPlugin\src\classes\services;

use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\MainSettingsModel;

class OptionsPageService
{
    private $message;
    private $code;
    private $langService;

    /**
     * Constructor
     */
    public function __construct($data = null) {
        $this->langService = new LanguageService('adminMenu');

        if($data !== null) {
            $result = $this->save_plugin_settings_data($data);
            if($result === true) {
                $this->message = $this->langService->langData['save_success'];
                $this->code = 200;
            }
            else {
                $this->message = $this->langService->langData['save_failed'];
                $this->code = 400;
            }
        }
    }

    /**
     * Get response message and code after save data
     * 
     * @return void
     */
    public function get_response_after_save() {
        return ["message" => $this->message, "code" => $this->code];
    }

    /**
     * Save settings data
     * 
     * @param mixed data
     * @return bool
     */
    public function save_plugin_settings_data($data) {
        $mainSettings = new MainSettingsModel($data);
        $this->remove_incorrect_data($mainSettings);
        $result = $mainSettings->get(CalendarTypes::CALENDAR_OPTION);

        if($result === null) {
            $result = $mainSettings->create(CalendarTypes::CALENDAR_OPTION, $mainSettings);
            return boolval($result);
        }

        $result = $mainSettings->update($result->id, CalendarTypes::CALENDAR_OPTION, $mainSettings);
        return boolval($result);
    }

    /**
     * Remove incorrect data after settings page is saved
     * 
     * @param object
     * @return void
     */
    private function remove_incorrect_data(&$object) {
        if((boolval($object->oneDayView) === true)) {
            $object->horizontalCalendarGrid = false;
        }

        if((boolval($object->horizontalCalendarGrid) === true)) {
            $object->oneDayView = false;
        }
    }
}