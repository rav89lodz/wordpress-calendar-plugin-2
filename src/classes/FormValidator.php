<?php

namespace CalendarPlugin\src\classes;

class FormValidator
{
/**
     * Validation sequence for text
     * 
     * @param string text
     * @return string|null
     */
    public function validation_sequence_for_text($text) {
        if($this->is_valid_string($text)) {
            return $this->sanitize_string($text);
        }
        return null;
    }

    /**
     * Validation sequence for name
     * 
     * @param string name
     * @return string|null
     */
    public function validation_sequence_for_name($name) {
        if($this->is_valid_string($name)) {
            return $this->sanitize_name($name);
        }
        return null;
    }

    /**
     * Validation sequence for email
     * 
     * @param string email
     * @return string|null
     */
    public function validation_sequence_for_email($email) {
        if(str_contains($email, ",")) {
            $toReturn = [];
            foreach(explode(",", $email) as $element) {
                if($this->is_valid_email(trim($element))) {
                    $toReturn[] = $this->sanitize_string($element);
                }
            }
            return implode(",", $toReturn);
        }
        if($this->is_valid_email($email)) {
            return $this->sanitize_string($email);
        }
        return null;
    }

    /**
     * Validation sequence for phone
     * 
     * @param string phone
     * @return string|null
     */
    public function validation_sequence_for_phone($phone) {
        if($this->is_valid_phone_number($phone)) {
            return $this->sanitize_string($phone);
        }
        return null;
    }

    /**
     * Validation sequence for date
     * 
     * @param string date
     * @return string|null
     */
    public function validation_sequence_for_date($date) {
        if($this->is_valid_date($date)) {
            return $this->sanitize_string($date);
        }
        return null;
    }

    /**
     * Validation sequence for time
     * 
     * @param mixed time
     * @return string
     */
    public function validation_sequence_for_time($time) {
        if($this->is_valid_time($time)) {
            return $this->sanitize_string($time);
        }
        return "00:00";
    }

    /**
     * Validation sequence for time
     * 
     * @param mixed time
     * @return string|null
     */
    public function validation_sequence_for_time_with_null($time) {
        if($this->is_valid_time($time)) {
            return $this->sanitize_string($time);
        }
        return null;
    }

    /**
     * Validation sequence for array
     * 
     * @param mixed array
     * @return string|null
     */
    public function validation_sequence_for_array($array) {
        if($this->is_valid_array($array)) {
            $implode = implode(',', $array);
            return $this->sanitize_name($implode);
        }
        return null;
    }

    /**
     * Validation sequence for checkbox
     * 
     * @param mixed checkbox
     * @return bool
     */
    public function validation_sequence_for_checkbox($checkbox) {
        if($this->is_valid_array($checkbox) && $this->is_valid_bool($checkbox[0])) {
            return boolval($checkbox[0]);
        }
        if($this->is_valid_bool($checkbox)) {
            return boolval($checkbox);
        }
        return false;
    }

    /**
     * Sanitize string
     * 
     * @param string string
     * @return string
     */
    public function sanitize_string($string) {
        $string = htmlspecialchars($string);
        $string = stripslashes($string);
        $string = trim($string);
        return $string;
    }

    /**
     * Sanitize string to name
     * 
     * @param string name
     * @return string
     */
    public function sanitize_name($name) {
        $name = str_replace(['!', '*', '{', '}', '~', '^', '|', '<', '>', '+', '&', '?', '(', ')', '[', ']', '#', '@', '$', '%', ';', '\\', '/'], "", $name);
        $name = $this->sanitize_string($name);
        return $name;
    }    

    /**
     * Check value is an valid email
     * 
     * @param string email
     * @return bool
     */
    public function is_valid_email($email) {
        if($email !== null && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Check value is a valid phone number
     * 
     * @param string phone
     * @return bool
     */
    public function is_valid_phone_number($phone) {
        if($phone !== null && preg_match("/^[\d\s\-\+]{1,20}$/", $phone)) {
            return true;
        }
        return false;
    }

    /**
     * Check value is a valid url
     * 
     * @param string url
     * @return bool
     */
    public function is_valid_url($url) {
        if($url !== null && preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
            return true;
        }
        return false;
    }

    /**
     * Check value is an valid array
     * 
     * @param array array
     * @return bool
     */
    public function is_valid_array($array)
    {
        if($array !== null && is_array($array) && count($array) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Check value is a valid number
     * 
     * @param mixed number
     * @return bool
     */
    public function is_valid_number($number)
    {
        if($number !== null && is_numeric($number)) {
            return true;
        }
        return false;
    }

    /**
     * Check value is a valid string
     * 
     * @param string value
     * @return bool
     */
    public function is_valid_string($string)
    {
        if($string !== null && is_string($string) && ! empty($string)) {
            return true;
        }
        return false;
    }

    /**
     * Check value is a valid boolean
     * 
     * @param bool value
     * @return bool
     */
    public function is_valid_bool($bool)
    {
        if($bool !== null && (is_bool($bool) || in_array($bool, ["1", "0", 1, 0]) )) {
            return true;
        }
        return false;
    }

    /**
     * Check value has correct date format Y-m-d
     * 
     * @param mixed value
     * @return bool
     */
    public function is_valid_date($date)
    {
        if($date !== null && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            $date = explode('-', $date);
            if(count($date) === 3 && checkdate($date[1], $date[2], $date[0])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check value has correct date format Y-m-d
     * 
     * @param mixed value
     * @return bool
     */
    public function is_valid_date_2($date) {
        return strtotime($date);
    }

    /**
     * Check value has correct hex color format
     * 
     * @param mixed value
     * @return bool
     */
    public function is_valid_hex_color($color)
    {
        if($color !== null && preg_match("/^#[0-9A-Fa-f]{6}$/", $color)) {
            return true;
        }
        return false;
    }

    /**
     * Check value is in array
     * 
     * @param mixed value
     * @param array array
     * @return bool
     */
    public function is_in_array($value, $array)
    {
        if($value !== null && $array !== null && count($array) > 0 && is_string($value) && ! empty($value) && in_array($value, $array)) {
            return true;
        }
        return false;
    }

    /**
     * Check value is correct time format H:i:s
     * 
     * @param mixed value
     * @param bool param
     * @return bool
     */
    public function is_valid_time($time, $withSeconds = false) {
        if($withSeconds === true && $time !== null && preg_match("/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/", $time)) {
            return true;
        }
        if($withSeconds === false && $time !== null && preg_match("/^[0-9]{2}:[0-9]{2}$/", $time)) {
            return true;
        }
        return false;
    }

    /**
     * Check value is correct timestamp
     * 
     * @param mixed value
     * @return bool
     */
    public function is_valid_timestamp($timestamp)
    {
        if($timestamp !== null && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $timestamp)) {
            return true;
        }
        return false;
    }

     /**
     * Check value is correct select field
     * 
     * @param mixed value
     * @return bool
     */
    public function is_valid_select_field($select) {
        if($select !== null && preg_match("/^\d{2}:\d{2}$/", $select)) {
            return true;
        }
        return false;
    }
}