<?php

namespace CalendarPlugin\src\classes\models;

use CalendarPlugin\src\classes\consts\CalendarTypes;

class MessageModel extends Model
{
    private $messageSuccess;
    private $messageError;
    private $messageFormSended;

    /**
     * Constructor
     */
    public function __construct() {
        $this->messageSuccess = $this->set_massage_option('messageSuccess');
        $this->messageError = $this->set_massage_option('messageError');
        $this->messageFormSended = $this->set_massage_option('reservationSendMessage');
    }

    /**
     * Get messageSuccess
     * 
     * @return string|null
     */
    public function get_message_success() {
        return $this->messageSuccess;
    }

    /**
     * Get messageError
     * 
     * @return string|null
     */
    public function get_message_error() {
        return $this->messageError;
    }

    /**
     * Get messageFormSended
     * 
     * @return string|null
     */
    public function get_message_form_sended() {
        return $this->messageFormSended;
    }

    /**
     * Set message from DB
     * 
     * @param string option name
     * @return string|null
     */
    private function set_massage_option($option) {
        $model = $this->get(CalendarTypes::CALENDAR_OPTION);
        if($model !== null) {
            return $model->$option;
        }
        return null;
    }
}