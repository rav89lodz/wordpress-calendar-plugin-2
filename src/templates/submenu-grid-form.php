<?php

use CalendarPlugin\src\classes\consts\CalendarSort;
use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\ActivityModel;
use CalendarPlugin\src\classes\models\PlaceModel;
use CalendarPlugin\src\classes\services\LanguageService;
use CalendarPlugin\src\classes\services\PaginationService;
use CalendarPlugin\src\classes\services\GridPageService;
use CalendarPlugin\src\classes\services\SearchService;
use CalendarPlugin\src\classes\services\SessionService;
use CalendarPlugin\src\classes\services\SortService;
use CalendarPlugin\src\classes\services\ValidationService;

if(isset($_POST['activity_name'])) {
    $validationService = new ValidationService;
    $data = $validationService->validate_data($_POST);
    $ppService = new GridPageService($data);
}

$service = new LanguageService(['optionPage', 'adminMenu', 'days', 'addActivityFriendlyNames', 'searchBar']);
$places = new PlaceModel();
$places = $places->all(CalendarTypes::CALENDAR_PLACE, true);

$activities = new ActivityModel();
$activities = $activities->all(CalendarTypes::CALENDAR_DATA, true);

SessionService::destroySessionSequenceForCalendarGridForm();

$order_vector = CalendarSort::ASC;

if(isset($_POST['activity_order_vector']) && isset($_POST['activity_order_by'])) {
    $_SESSION['activity_order_vector'] = $_POST['activity_order_vector'] === CalendarSort::ASC ? CalendarSort::DESC : CalendarSort::ASC;
    $_SESSION['activity_order_by'] = $_POST['activity_order_by'];
}

if(isset($_SESSION['activity_order_by']) && isset($_SESSION['activity_order_vector'])) {
    $order_vector = $_SESSION['activity_order_vector'];
    $activities = SortService::sortBy($activities, $_SESSION['activity_order_by'],  $_SESSION['activity_order_vector']);
}

if(isset($_POST['search_bar_input_serach'])) {
    $_SESSION['search_bar_input_activity'] = $_POST['search_bar_input_serach'];
    $_SESSION['search_bar_option_activity'] = $_POST['search_bar_option_serach'];
    $_SESSION['search_bar_field_activity'] = $_POST['search_bar_field_serach'];
}

if(isset($_SESSION['search_bar_input_activity']) && isset($_SESSION['search_bar_option_activity']) && isset($_SESSION['search_bar_field_activity'])) {
    $activities = SearchService::search($activities, $_SESSION['search_bar_field_activity'], $_SESSION['search_bar_option_activity'], $_SESSION['search_bar_input_activity']);
}

$pagination = PaginationService::paginate($activities);

$placeData = [];
foreach($places as $place) {
    $placeData[] = $place->id . ">?>" . $place->name;
}

?>

<style>
    #wpcontent {
        padding-left: 0 !important;
    }
</style>

<input type="hidden" id="get_rest_url" value="<?= get_rest_url( null, 'v1') ?>">

<div class="container-fluid">
    <div>
        <h2><?= $service->langData['calendar_grid_data_title'] ?></h2>
    </div>

    <button type="button" id="calendarFormModalAddGridButton" class="btn btn-primary" data-toggle="modal" data-target="#calendarFormModalAddGrid" data-places="<?= implode("|,|", $placeData) ?>"><?= $service->langData['add_new'] ?></button>

    <div class="my-plugin-search-bar">
        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid' ?>">
            <div style="float: left;">
                <select class="form-control my-plugin-search-bar-input" name="search_bar_field_serach" aria-label="Select field">
                    <option value="id" selected>Id</option>
                    <option value="name"><?= $service->langData['title_label_grid'] ?></option>
                    <option value="type"><?= $service->langData['activity_type_place'] ?></option>
                    <option value="startAt"><?= $service->langData['add_activity_time_start'] ?></option>
                    <option value="dates"><?= $service->langData['grid_activity_days'] ?></option>
                </select>
            </div>
            <div style="float: left;">
                <select class="form-control my-plugin-search-bar-input" name="search_bar_option_serach" aria-label="Select option">
                    <option selected value="option_serach_1"><?= $service->langData['search_bar_option_serach1'] ?></option>
                    <option value="option_serach_2"><?= $service->langData['search_bar_option_serach2'] ?></option>
                    <option value="option_serach_3"><?= $service->langData['search_bar_option_serach3'] ?></option>
                </select>
            </div>
            <div style="float: left;">
                <input class="form-control my-plugin-search-bar-input" name="search_bar_input_serach" required type="search" placeholder="<?= $service->langData['search'] ?>" aria-label="Search">
            </div>
            <div style="float: left;">
                <button class="btn btn-primary btn-search my-plugin-search-bar-button" type="submit"><?= $service->langData['search_btn'] ?></button>
            </div>
            <div style="clear: both;"></div>
        </form>
    </div>

    <div class="d-flex justify-content-center">
        <div class="modal fade" id="calendarFormModalAddGrid" tabindex="-1" role="dialog" aria-labelledby="calendarFormModalAddGrid" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid' ?>">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group my-plugin-form-group">
                                        <label for="activity_name"><?= $service->langData['calendar_grid_data_field1_name'] ?></label>
                                        <input type="text" class="form-control" id="activity_name" name="activity_name">
                                        <div id="activity_grid_form_group"></div>
                                    </div>

                                    <hr class="hr2">

                                    <div class="form-group my-plugin-form-group">
                                        <label for="activity_start_at"><?= $service->langData['calendar_grid_data_field2_name'] ?></label>
                                        <input type="time" class="form-control" id="activity_start_at" name="activity_start_at" step="300" pattern="[0-9]{2}:[0-9]{2}" value="12:00">
                                    </div>

                                    <hr class="hr2">

                                    <div class="form-group my-plugin-form-group">
                                        <label for="activity_end_at"><?= $service->langData['calendar_grid_data_field3_name'] ?></label>
                                        <input type="time" class="form-control" id="activity_end_at" name="activity_end_at" step="300" pattern="[0-9]{2}:[0-9]{2}" value="13:00">
                                    </div>

                                    <hr class="hr2">

                                    <div class="form-group my-plugin-form-group">
                                        <label for="activity_type"><?= $service->langData['calendar_grid_data_field8_name'] ?></label>
                                        <select class="form-control" id="activity_type" name="activity_type">
                                            <?php
                                                $i = 0;
                                                foreach($places as $place) {
                                                    if($i == 0) {
                                                        echo '<option value="' . $place->id . '" selected>' . $place->name . '</option>';
                                                    }
                                                    else {
                                                        echo '<option value="' . $place->id . '">' . $place->name . '</option>';
                                                    }
                                                    $i ++;
                                                }
                                            ?>
                                        </select>
                                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field8_description'] ?></small>
                                    </div>

                                    <hr class="hr2">

                                    <div class="form-group my-plugin-form-group">
                                        <label for="activity_bg_color"><?= $service->langData['calendar_grid_data_field7_name'] ?></label>
                                        <input type="color" class="form-control" id="activity_bg_color" name="activity_bg_color" value="#ff0000">
                                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field7_description'] ?></small>
                                    </div>

                                    <hr class="hr2">

                                    <div class="form-group my-plugin-form-group">
                                        <label for="activity_slot"><?= $service->langData['calendar_grid_data_field9_name'] ?></label>
                                        <input type="number" class="form-control" id="activity_slot" name="activity_slot" min="1" value="1">
                                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field9_description'] ?></small>
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="form-check admin-field-mb">
                                        <div class="admin-check-input">
                                            <input class="form-check-input admin-field-checkbox" type="checkbox" id="activity_cyclic">
                                            <input type='hidden' value='0' name='activity_cyclic' id="activity_cyclic_hidden">
                                            <label class="form-check-label" for="activity_cyclic"><?= $service->langData['calendar_grid_data_field4_name'] ?></label>
                                        </div>
                                    </div>

                                    <hr class="hr2">

                                    <div id="activity_date_div">
                                        <div class="form-group my-plugin-form-group">
                                            <label for="activity_date"><?= $service->langData['calendar_grid_data_field5_name'] ?></label>
                                            <input type="date" class="form-control" id="activity_date" name="activity_date" min="<?= date('Y-m-d'); ?>" required>
                                            <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field5_description'] ?></small>
                                        </div>
                                    </div>
                            
                                    <div id="activity_day_div" class="d-none">
                                        <div class="form-group my-plugin-form-group">
                                            <label for="activity_day"><?= $service->langData['calendar_grid_data_field6_name'] ?></label>
                                            <select class="form-control" id="activity_day" name="activity_day[]" multiple>
                                                <option value="monday" selected><?= $service->langData['monday'] ?></option>
                                                <option value="tuesday"><?= $service->langData['tuesday'] ?></option>
                                                <option value="wednesday"><?= $service->langData['wednesday'] ?></option>
                                                <option value="thursday"><?= $service->langData['thursday'] ?></option>
                                                <option value="friday"><?= $service->langData['friday'] ?></option>
                                                <option value="saturday"><?= $service->langData['saturday'] ?></option>
                                                <option value="sunday"><?= $service->langData['sunday'] ?></option>
                                            </select>
                                        </div>

                                        <hr class="hr2">

                                        <div class="form-group my-plugin-form-group">
                                            <label for="activity_day_start_date"><?= $service->langData['calendar_grid_data_field11_name'] ?></label>
                                            <input type="date" class="form-control" id="activity_day_start_date" name="activity_day_start_date" min="<?= date('Y-m-d'); ?>">
                                            <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field11_description'] ?></small>
                                        </div>

                                        <hr class="hr2">

                                        <div class="form-group my-plugin-form-group">
                                            <label for="activity_day_end_date"><?= $service->langData['calendar_grid_data_field12_name'] ?></label>
                                            <input type="date" class="form-control" id="activity_day_end_date" name="activity_day_end_date" min="<?= date('Y-m-d'); ?>">
                                            <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field12_description'] ?></small>
                                        </div>

                                        <hr class="hr2">

                                        <div class="form-group my-plugin-form-group">
                                            <label for="activity_exclusion_days"><?= $service->langData['calendar_grid_data_field13_name'] ?></label>
                                            <textarea class="form-control" id="activity_exclusion_days" name="activity_exclusion_days" rows="3"></textarea>
                                            <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field13_description'] ?></small>
                                        </div>

                                    </div>
                                </div>
                            </div>                            

                            <input type="hidden" name="activity_is_active" id="activity_is_active" value="1">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close btn btn-secondary" data-dismiss="modal"><?= $service->langData['form_cancel'] ?></button>
                            <button type="submit" class="btn btn-success"><?= $service->langData['save'] ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="calendarFormModalRemoveGridItem" tabindex="-1" role="dialog" aria-labelledby="calendarFormModalRemoveGridItem" aria-hidden="true">
            <div class="modal-dialog modal modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid' ?>">
                        <div class="modal-body">
                            <div class="text-center">
                                <h3><?= $service->langData['delete_question'] ?></h3>
                                <h4 id="plugin_grid_item_to_remove"></h4>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="activity_name" value="delete">
                                <div id="activity_grid_form_group_2"></div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="close btn btn-info" data-dismiss="modal"><?= $service->langData['form_cancel'] ?></button>
                            <button type="submit" class="btn btn-danger"><?= $service->langData['delete'] ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="max-width: 90%">
        <div id="copyNotification" class="plugin-notification-copy-clipboard" style="display: none;"><?= $service->langData['copy_clipboard'] ?></div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid' ?>">
                            <input type="hidden" name="activity_order_by" value="id">
                            <input type="hidden" name="activity_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;">Id</a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid' ?>">
                            <input type="hidden" name="activity_order_by" value="name">
                            <input type="hidden" name="activity_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['title_label_grid'] ?></a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid' ?>">
                            <input type="hidden" name="activity_order_by" value="type">
                            <input type="hidden" name="activity_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['activity_type_place'] ?></a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid' ?>">
                            <input type="hidden" name="activity_order_by" value="startAt">
                            <input type="hidden" name="activity_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['add_activity_time_start'] ?></a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid' ?>">
                            <input type="hidden" name="activity_order_by" value="dates">
                            <input type="hidden" name="activity_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['grid_activity_days'] ?></a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid' ?>">
                            <input type="hidden" name="activity_order_by" value="isActive">
                            <input type="hidden" name="activity_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['activity_is_active'] ?></a>
                        </form>
                    </th>
                    <th scope="col"><?= $service->langData['edit_label'] ?></th>
                    <th scope="col"><?= $service->langData['delete_label'] ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($pagination as $activity) {
                            echo "<tr>";
                            echo "<td style='background-color:" . $activity->bgColor . ";'>" . $activity->id . "</td>";
                            echo "<td>" . $activity->name . "</td>";
                            echo "<td>" . $activity->type . "</td>";
                            echo "<td>" . $activity->startAt . "</td>";

                            $days = $activity->date;
                            if($activity->date === null && $activity->day !== null) {
                                $tempDays = [];
                                foreach(explode(',', $activity->day) as $element) {
                                    $tempDays[] =  $service->langData[$element];
                                }
                                $days = implode(', ', $tempDays);
                            }

                            echo "<td>" . $days . "</td>";
                            $checked = $activity->isActive === true ? "checked" : "";
                            $isActive = $activity->isActive === true ? "1" : "0";
                            echo "<td><input type='checkbox' class='calendar-activity-is-active' data-target='" . $activity->id . "' " . $checked . "></td>";
                            unset($activity->created_at);
                            unset($activity->deleted_at);
                            unset($activity->calendar_key_type);
                            unset($activity->duration);
                            unset($activity->rawType);
                            $activity->isActive = $isActive;
                            $data = implode("|,|", (array) $activity);
                            echo '<td><button class="btn btn-sm btn-info btn-grid-update-action" type="button" data-places="' . implode("|,|", $placeData) . '" data-target="' .  $data . '">' . $service->langData['edit'] . '</button></td>';
                            echo '<td><button class="btn btn-sm btn-danger btn-grid-delete-action" type="button" data-target="' .  $data . '">' . $service->langData['delete'] . '</button></td>';
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div><?= PaginationService::get_links() ?></div>
    </div>
</div>