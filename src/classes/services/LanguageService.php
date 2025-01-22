<?php

namespace CalendarPlugin\src\classes\services;

class LanguageService
{
    public $langData;

    /**
     * Constructor
     *
     * @param string|array arrayName
     */
    public function __construct($arrayName = null) {
        switch(get_locale()) {
            case 'pl_PL':
                $this->set_calendar_plugin_language('pl', $arrayName);
                break;
            default:
                $this->set_calendar_plugin_language('en', $arrayName);
                break;
        }    
    }

    /**
     * Set properties for current language
     * 
     * @param string lang
     * @param string|array arrayName
     */
    private function set_calendar_plugin_language($lang, $arrayName) {
        $config = include CALENDAR_PLUGIN_PATH . '/src/lang/' . $lang . '.php';

        if(is_array($arrayName)) {
            $tempData = [];
            foreach($arrayName as $name) {
                $tempData[] = $config[$name];
            }

            $this->langData = array_merge(...$tempData);
        }
        else {
            $this->langData = $config[$arrayName];
        }
    }    
}