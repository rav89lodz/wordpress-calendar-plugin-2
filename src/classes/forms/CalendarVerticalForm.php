<?php

namespace CalendarPlugin\src\classes\forms;

class CalendarVerticalForm extends CalendarForm
{
    private $calendar;
    private $shortCode;

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
    public function create_vertical_table_header() {
        $days = [
            $this->langService->langData['monday'],
            $this->langService->langData['tuesday'],
            $this->langService->langData['wednesday'],
            $this->langService->langData['thursday'],
            $this->langService->langData['friday'],
            $this->langService->langData['saturday'],
            $this->langService->langData['sunday']
        ];
        $currentDay = $this->calendar->get_cuttent_monday_date();

        echo '<th scope="col"><input type="hidden" id="grid_vector" value="V">';
        for($i = 0; $i < 7; $i ++) {
            if($i > 0) {
                $currentDay = $this->calendar->get_monday_plus_days($i);
            }

            $this->print_header_row($days[$i], $currentDay, $i + 1);
            $this->datesOnThisWeek[] = $currentDay;
        }
        echo "</th>";
    }

    /**
     * Create table content
     * 
     * @return void
     */
    public function create_vertical_table_content() {
        $activities = $this->get_all_activities_models($this->shortCode);

        $endTime = strtotime($this->calendar->get_last_hour_on_calendar());
        $interval = "+" . $this->calendar->get_calendar_interval() . " minutes";
        
        $currentTime = strtotime($this->calendar->get_first_hour_on_calendar());
        while ($currentTime <= $endTime) {
            $activitiesGrouped = $this->group_activities_by_day_and_hour($activities, date('H:i', $currentTime));

            $this->print_row_with_data($activitiesGrouped, date('H:i', $currentTime));

            for($min = 5; $min < $this->calendar->get_calendar_interval() - 4; $min += 5) {
                $minute = date('H:i', strtotime("+$min minutes", $currentTime));
                if (isset($activitiesGrouped[$minute])) {
                    $this->print_row_with_data($activitiesGrouped, $minute);
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
     * @return void
     */
    private function print_row_with_data($groupedActivities, $currentTime) {
        echo "<tr>";
        echo "<td>" . $currentTime . "</td>";
    
        for ($day = 1; $day <= 7; $day++) {
            $excluded = $this->is_excluded_day_by_global_exclusion($this->datesOnThisWeek[$day - 1], $currentTime);
            if($excluded !== null) {
                $this->get_row_with_exclusion_data($this->calendar, $excluded);
                continue;
            }

            if (! empty($groupedActivities[$currentTime][$day])) {

                if($this->calendar->get_fluent_calendar_grid() === false) {
                    echo "<td>";
                }
                else {
                    echo "<td><div class='flex-cell'>";
                }
                
                foreach ($groupedActivities[$currentTime][$day] as $activity) {
                    $this->get_cell_with_activity($this->calendar, $activity, $currentTime, $day);
                }
                if($this->calendar->get_fluent_calendar_grid() === false) {
                    echo "</td>";
                }
                else {
                    echo "</div></td>";
                }
            }
            else {
                echo "<td></td>";
            }
        }
        echo "</tr>";
    }

    /**
     * Print header row
     * 
     * @param string dayName
     * @param string date
     * @param string id
     * @return void
     */
    private function print_header_row($dayName, $date, $id) {
        $id = "header_$id";
        echo "<td><p>" . $dayName . "</p><span id='$id'>" . $date . "</span></td>";
    }
}