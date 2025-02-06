<?php

namespace CalendarPlugin\src\classes\forms;

class CalendarOneDayForm extends CalendarForm
{
    private $calendar;
    private $shortCode;
    private $currentWeekDay;
    public $currentDayName;

    /**
     * Constructor
     *
     * @param object|null calendar
     * @param mixed shortCode
     */
    public function __construct($calendar = null, $shortCode = null )
    {
        parent::__construct();
        $this->calendar = $calendar;
        $this->shortCode = $shortCode;
    }

    /**
     * Create table header
     * 
     * @return void
     */
    public function create_one_day_table_header() {
        $currentDay = $this->calendar->get_cuttent_date();
        $this->currentWeekDay = date('w', strtotime($currentDay));
        if($this->currentWeekDay == 0) {
            $this->currentWeekDay = 7;
        }

        echo '<input type="hidden" id="grid_vector" value="V">';
        $this->print_header_row($currentDay, $this->currentWeekDay);
        $this->datesOnThisWeek[] = $currentDay;
    }

    /**
     * Create table content
     * 
     * @return void
     */
    public function create_one_day_table_content() {
        $activities = $this->get_all_activities_models($this->shortCode);

        $endTime = strtotime($this->calendar->get_last_hour_on_calendar());
        $interval = "+" . $this->calendar->get_calendar_interval() . " minutes";
        
        $currentTime = strtotime($this->calendar->get_first_hour_on_calendar());
        while ($currentTime <= $endTime) {
            $excluded = $this->is_excluded_day_by_global_exclusion($this->datesOnThisWeek[0], date('H:i', $currentTime));
            if($excluded !== null) {
                echo "<tr>";
                echo "<td style='width:15%'>" . date('H:i', $currentTime) . "</td>";
                $this->get_row_with_exclusion_data($this->calendar, $excluded);
                echo "</tr>";

                $currentTime = strtotime($interval, $currentTime);
                continue;
            }

            $activitiesGrouped = $this->group_activities_by_day_and_hour($activities, date('H:i', $currentTime));
            $this->print_row_with_data($activitiesGrouped, date('H:i', $currentTime), $this->currentWeekDay);

            for($min = 5; $min < $this->calendar->get_calendar_interval() - 4; $min += 5) {
                $minute = date('H:i', strtotime("+$min minutes", $currentTime));
                if (isset($activitiesGrouped[$minute])) {
                    $this->print_row_with_data_second_class($activitiesGrouped, $minute, $this->currentWeekDay);
                }
            }

            $currentTime = strtotime($interval, $currentTime);
        }
    }

    /**
     * Print table row with data
     * 
     * @param array groupedActivities
     * @param string currentTime
     * @param int day
     * @return void
     */
    private function print_row_with_data($groupedActivities, $currentTime, $day) {
        echo "<tr>";
        echo "<td style='width:15%'>" . $currentTime . "</td>";
    
        if (! empty($groupedActivities[$currentTime][$day])) {
            echo "<td><div class='flex-cell'>";
            foreach ($groupedActivities[$currentTime][$day] as $activity) {
                $this->get_cell_with_activity($this->calendar, $activity, $currentTime, 1, "_" . $this->currentWeekDay);
            }
            echo "</div></td>";
        }
        else {
            echo "<td></td>";
        }
        echo "</tr>";
    }

    /**
     * Print table row with data with minutes iterration
     * 
     * @param array groupedActivities
     * @param string currentTime
     * @param int day
     * @return void
     */
    private function print_row_with_data_second_class($groupedActivities, $currentTime, $day) {    
        if (! empty($groupedActivities[$currentTime][$day])) {
            echo "<tr>";
            echo "<td style='width:15%'>" . $currentTime . "</td>";

            echo "<td><div class='flex-cell'>";
            foreach ($groupedActivities[$currentTime][$day] as $activity) {
                $this->get_cell_with_activity($this->calendar, $activity, $currentTime, 1, "_" . $this->currentWeekDay);
            }
            echo "</div></td>";
            echo "</tr>";
        }        
    }

    /**
     * Print header row
     * 
     * @param string date
     * @param string id
     * @return void
     */
    private function print_header_row($date, $id) {
        $id = "header_$id";
        echo "<span style='display:none' id='$id'>" . $date . "</span>";
    }
}