<?php

namespace CalendarPlugin\src\classes\forms;

use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\ActivityModel;
use CalendarPlugin\src\classes\services\LanguageService;
use CalendarPlugin\src\classes\services\ReservationService;

abstract class CalendarForm
{
    protected $reservationService;
    protected $langService;
    protected $datesOnThisWeek;

    /**
     * Constructor
     */
    public function __construct() {
        $this->reservationService = new ReservationService();
        $this->langService = new LanguageService(['calendarLabels', 'days', 'months']);
        $this->datesOnThisWeek = [];
    }

    /**
     * Get all activities models from DB
     * 
     * @return array
     */
    protected function get_all_activities_models($shortCode) {
        $model = new ActivityModel();
        $toReturn = [];
        $rows = $model->all(CalendarTypes::CALENDAR_DATA);

        foreach($rows as $row) {
            if(isset($row->isActive) && $row->isActive === false) {
                continue;
            }

            $date = $row->date ? 'empty' : $row->date;
            $time = str_contains($row->startAt, ":") ? $row->startAt : "30:80";
            $key = $date . "#" . $time . "#" . $row->id;

            if($shortCode === null || count($shortCode) < 1) {
                $toReturn[$key] = $row;
                continue;
            }

            $skip = true;
            foreach($shortCode as $code) {
                if($row->rawType == $code){
                    $skip = false;
                    break;
                }
            }
            if($skip == true) {
                continue;
            }
            $toReturn[$key] = $row;
        }

        return $toReturn;
    }

    /**
     * Create cell with data
     * 
     * @param object|null calendar
     * @param object|null activity
     * @param string currentTime
     * @param string|int day
     * @param string|null oneDayId
     * @return void
     */
    protected function get_cell_with_activity($calendar, $activity, $currentTime, $day, $oneDayId = null) {
        $id = $activity->id . "_" . $currentTime . "_" . $day . $oneDayId;
        $limit = $this->reservationService->check_reservation_limit($activity->id, $this->datesOnThisWeek[$day - 1]);

        $class = $this->set_fulent_background_class($calendar, $activity->bgColor, $activity->id);

        if($limit >= $activity->slot || $calendar->get_calendar_reservation() === false) {
            echo "<div data-info='" . $activity->startAt . "|" . $activity->endAt . "|" . $activity->duration . "' class='calendar-event cursor-default $class' id='$id' style='background-color: " . htmlspecialchars($activity->bgColor) . ";'>";
        }
        else {
            echo "<div data-info='" . $activity->startAt . "|" . $activity->endAt . "|" . $activity->duration . "' class='calendar-event cursor-pointer $class' id='$id' style='background-color: " . htmlspecialchars($activity->bgColor) . ";'>";
        }
        
        if($calendar->get_duration_on_grid() === true) {
            echo "<span>" . htmlspecialchars($activity->duration) . " min</span>";
        }
        echo "<p class='text-wrap' style='font-weight: bold;'>" . htmlspecialchars($activity->name) . "</p>";
        if($calendar->get_place_activity_on_grid() === true) {
            echo "<p class='text-wrap'>" . htmlspecialchars($activity->type) . "</p>";
        }

        if($calendar->get_start_time_on_grid() === true && $calendar->get_end_time_on_grid() === true) {
            echo "<p>" . $this->langService->langData['label_activity_from'] . " " . htmlspecialchars($activity->startAt) . " "
                . $this->langService->langData['label_activity_to'] . " " . htmlspecialchars($activity->endAt) . "</p>";
        }

        if($calendar->get_start_time_on_grid() === true && $calendar->get_end_time_on_grid() === false) {
            echo "<p>" . $this->langService->langData['label_activity_start_at'] . " " . htmlspecialchars($activity->startAt) . "</p>";
        }
        
        if($calendar->get_start_time_on_grid() === false && $calendar->get_end_time_on_grid() === true) {
            echo "<p>" . $this->langService->langData['label_activity_end_at'] . " " . htmlspecialchars($activity->endAt) . "</p>";
        }
        
        echo "</div>";
    }

    /**
     * Create css class for fluent background option
     * 
     * @param string color
     * @param string classId
     * @param string height
     * @return string
     */
    protected function set_fulent_background_class($calendar , $color, $classId) {
        if($calendar->get_fluent_calendar_grid() === false) {
            return null;
        }
        $className = "abc$classId-" . $this->generate_random_string(12);

        if($calendar->get_horizontal_calendar_grid() === true) {
            $whClassParam = "height: 100%; width: var(--after-height); top: 0%;";
        }
        else {
            $whClassParam = "width: 100%; height: var(--after-height); top: 100%;";
        }

        echo "<style>";
        echo '.' . $className .' {
            --after-height: 0px;
        }';
        echo '.' . $className .'::after {
                content: "";
                position: absolute;
                left: 0;'
                . $whClassParam .
                ' background-color:' . $color . ';
                z-index: -1;
            }';
        echo "</style>";
        return $className;
    }

    /**
     * Group activities by day and hour
     * 
     * @param array activities
     * @param string currentTime
     * @param string|null currentDay
     * @return array
     */
    protected function group_activities_by_day_and_hour($activities, $currentTime, $currentDay = null) {
        $groupedActivities = [];
        $isHorizontal = true;

        if($currentDay === null) {
            $isHorizontal = false;
        }

        foreach ($activities as $activity) {
            if ($activity->isCyclic === true) {
                $loop = $this->attach_day_of_week_as_number($activity);
                foreach ($loop as $dayOfWeek) {
                    $groupedActivities = $this->attach_activity_to_array($groupedActivities, $activity, $currentTime, $dayOfWeek, $isHorizontal);
                }
            }
            else {
                if (in_array($activity->date, $this->datesOnThisWeek) || ($currentDay !== null && $activity->date == $currentDay)) {
                    $dayOfWeek = date('N', strtotime($activity->date));
                    $groupedActivities = $this->attach_activity_to_array($groupedActivities, $activity, $currentTime, $dayOfWeek, $isHorizontal);
                }
            }
        }
    
        return $groupedActivities;
    }

    /**
     * Change day name to day number
     * 
     * @param object activity
     * @return array
     */
    private function attach_day_of_week_as_number($activity) {
        $days = explode(',', $activity->day);
        $newDays = [];
        foreach($days as $day) {
            switch($day) {
                case 'monday':
                    $newDays[] = 1;
                    break;
                case 'tuesday':
                    $newDays[] = 2;
                    break;
                case 'wednesday':
                    $newDays[] = 3;
                    break;
                case 'thursday':
                    $newDays[] = 4;
                    break;
                case 'friday':
                    $newDays[] = 5;
                    break;
                case 'saturday':
                    $newDays[] = 6;
                    break;
                case 'sunday':
                    $newDays[] = 7;
                    break;
            }
        }
        return $newDays;
    }

    /**
     * Attach single activity to passed array on specific position
     * 
     * @param array groupedActivities
     * @param object|null activity
     * @param string currentTime
     * @param string|int dayOfWeek
     * @param bool isHorizontal
     * @return array
     */
    private function attach_activity_to_array($groupedActivities, $activity, $currentTime, $dayOfWeek, $isHorizontal) {
        if($isHorizontal === true) {
            if ($activity->startAt == $currentTime) {
                $groupedActivities[$dayOfWeek][$currentTime][] = $activity;
            }
            else {
                $minuteHour = date('H:i', strtotime($activity->startAt));
                if (!isset($groupedActivities[$dayOfWeek][$minuteHour])) {
                    $groupedActivities[$dayOfWeek][$minuteHour] = [];
                }
                $groupedActivities[$dayOfWeek][$minuteHour][] = $activity;
            }
        }
        else {
            if ($activity->startAt == $currentTime) {
                $groupedActivities[$currentTime][$dayOfWeek][] = $activity;
            }
            else {
                $minuteHour = date('H:i', strtotime($activity->startAt));
                if (!isset($groupedActivities[$minuteHour])) {
                    $groupedActivities[$minuteHour] = [];
                }
                $groupedActivities[$minuteHour][$dayOfWeek][] = $activity;
            }
        }
        
        return $groupedActivities;
    }

    /**
     * Generate random string
     * 
     * @param int length
     * @return string
     */
    private function generate_random_string($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
    
        return $randomString;
    }
}