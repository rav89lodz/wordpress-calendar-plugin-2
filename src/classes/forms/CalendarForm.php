<?php

namespace CalendarPlugin\src\classes\forms;

use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\ActivityModel;
use CalendarPlugin\src\classes\models\ExcludedActivityModel;
use CalendarPlugin\src\classes\services\LanguageService;
use CalendarPlugin\src\classes\services\ReservationService;

abstract class CalendarForm
{
    protected $reservationService;
    protected $langService;
    protected $datesOnThisWeek;
    protected $exclusionData;

    /**
     * Constructor
     */
    public function __construct() {
        $this->reservationService = new ReservationService();
        $this->langService = new LanguageService(['calendarLabels', 'days', 'months']);
        $this->datesOnThisWeek = [];
        $this->exclusionData = new ExcludedActivityModel();
        $this->exclusionData = $this->exclusionData->all(CalendarTypes::CALENDAR_EXCLUDED_ACTIVITY);
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
        if($this->is_date_in_cyclic_events_range_dates($activity, $this->datesOnThisWeek[$day - 1]) === false) {
            echo "";
            return;
        }

        if($this->is_excluded_day($activity, $this->datesOnThisWeek[$day - 1]) === true) {
            echo "";
            return;
        }

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

        $this->show_hours_on_grid_calendar($calendar, $activity->startAt, $activity->endAt, false);
        
        echo "</div>";
    }

    /**
     * Show hours on calendar grid
     * 
     * @param object|null calendar
     * @param string|null startAt
     * @param string|null endAt
     * @param bool isExcluded
     * @return void 
     */
    private function show_hours_on_grid_calendar($calendar, $startAt, $endAt, $isExcluded) {
        if($calendar->get_start_time_on_grid() === true && $calendar->get_end_time_on_grid() === true) {
            $labelFrom = $this->langService->langData['label_activity_from'];

            if($isExcluded === true) {
                $labelFrom = $this->langService->langData['label_excluded_from'];
            }
            
            if($startAt !== null && $endAt !== null) {
                echo "<p>" . $labelFrom . " " . htmlspecialchars($startAt) . " " . $this->langService->langData['label_activity_to'] .
                    " " . htmlspecialchars($endAt) . "</p>";
            }

            if($isExcluded === true && $startAt === null && $endAt === null) {
                echo "<p>" . $this->langService->langData['label_excluded_all_day_long'] . "</p>";
            }
        }

        if($calendar->get_start_time_on_grid() === true && $calendar->get_end_time_on_grid() === false) {
            $labelFrom = $this->langService->langData['label_activity_start_at'];

            if($isExcluded === true) {
                $labelFrom = $this->langService->langData['label_excluded_start_at'];
            }

            if($startAt !== null) {
                echo "<p>" . $labelFrom . " " . htmlspecialchars($startAt) . "</p>";
            }
        }
        
        if($calendar->get_start_time_on_grid() === false && $calendar->get_end_time_on_grid() === true) {
            $labelTo = $this->langService->langData['label_activity_end_at'];

            if($isExcluded === true) {
                $labelTo = $this->langService->langData['label_excluded_end_at'];
            }

            if($endAt !== null) {
                echo "<p>" . $labelTo . " " . htmlspecialchars($endAt) . "</p>";
            }
        }
    }

    /**
     * Check date is in excluded days by global exclusion
     * 
     * @param string date
     * @param string time
     * @return object|null
     */
    protected function is_excluded_day_by_global_exclusion($date, $time) {
        $timestampDate = strtotime($date);
        $timestampTime = strtotime($time);

        foreach($this->exclusionData as $element) {
            if($element->excludedIsActive === false) {
                continue;
            }

            $elementDateTimestamp = strtotime($element->excludedDate);
            $elementStartTimestamp = strtotime($element->excludedStartAt);
            $elementEndTimestamp = strtotime($element->excludedEndAt);

            if ($element->excludedStartAt === null && $element->excludedEndAt === null) {
                if ($timestampDate == $elementDateTimestamp) {
                    return $element;
                }
            }
            elseif ($element->excludedStartAt !== null && $element->excludedEndAt === null) {
                if ($timestampTime >= $elementStartTimestamp && $timestampDate == $elementDateTimestamp) {
                    return $element;
                }
            }
            elseif ($element->excludedStartAt === null && $element->excludedEndAt !== null) {
                if ($timestampTime <= $elementEndTimestamp && $timestampDate == $elementDateTimestamp) {
                    return $element;
                }
            }
            else {
                if ($timestampTime >= $elementStartTimestamp && $timestampTime <= $elementEndTimestamp && $timestampDate == $elementDateTimestamp) {
                    return $element;
                }
            }
        }
        return null;
    }

    /**
     * Get row with excluded data
     * 
     * @param object|null calendar
     * @param object|null excluded
     * @return void
     */
    protected function get_row_with_exclusion_data($calendar, $excluded) {
        echo "<td style='background-color: " . htmlspecialchars($excluded->excludedBgColor) .
                ";'><div><p class='text-wrap' style='font-weight: bold;'>" . $excluded->excludedName . "</p>";
        $this->show_hours_on_grid_calendar($calendar, $excluded->excludedStartAt, $excluded->excludedEndAt, true);
        echo "</div></td>";
    }

    /**
     * Check date is in excluded days
     * 
     * @param object|null activity
     * @param string date
     * @return bool
     */
    private function is_excluded_day($activity, $date) {
        if($activity->exclusionDays === null) {
            return false;
        }

        $exclusionDays = explode(";", $activity->exclusionDays);
        $timestamp = strtotime($date);

        foreach($exclusionDays as $oneDay) {
            if(str_contains($oneDay, "%")) {
                $oneDay = str_replace("%", date('Y', $timestamp), $oneDay);
            }

            $dateOneDay = strtotime($oneDay);

            if($dateOneDay === false) {
                continue;
            }

            if($timestamp == $dateOneDay) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check date range for cyclic events
     * 
     * @param object|null activity
     * @param string date
     * @return bool
     */
    private function is_date_in_cyclic_events_range_dates($activity, $date) {
        $startTimestamp = strtotime($activity->startDateForDay);
        $endTimestamp = strtotime($activity->endDateForDay);

        if($startTimestamp === false && $endTimestamp === false) {
            return true;
        }

        $timestamp = strtotime($date);

        if($startTimestamp === false && $timestamp <= $endTimestamp) {
            return true;
        }

        if($endTimestamp === false && $timestamp >= $startTimestamp) {
            return true;
        };

        if($timestamp >= $startTimestamp && $timestamp <= $endTimestamp) {
            return true;
        }       

        return false;
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