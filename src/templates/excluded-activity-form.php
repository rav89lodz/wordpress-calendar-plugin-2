<?php

use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\ExcludedActivityModel;
use CalendarPlugin\src\classes\models\PlaceModel;
use CalendarPlugin\src\classes\services\CalendarService;
use CalendarPlugin\src\classes\services\LanguageService;
use CalendarPlugin\src\classes\services\PaginationService;
use CalendarPlugin\src\classes\services\ExcludedActivityService;
use CalendarPlugin\src\classes\services\ValidationService;

if(isset($_POST['excluded_activity_name'])) {
    $validationService = new ValidationService;
    $data = $validationService->validate_data($_POST);
    $eaService = new ExcludedActivityService($data);
}

$service = new LanguageService(['optionPage', 'adminMenu', 'excludedActivityMenu', 'excludedActivityFriendlyNames']);
$calendarService = new CalendarService();

$excludedActivities = new ExcludedActivityModel();
$excludedActivities = $excludedActivities->all(CalendarTypes::CALENDAR_EXCLUDED_ACTIVITY, true);

$places = new PlaceModel();
$places = $places->all(CalendarTypes::CALENDAR_PLACE, true);

$placeData = [];
foreach($places as $place) {
    $placeData[] = $place->id . ">?>" . $place->name;
}

$pagination = PaginationService::paginate($excludedActivities);

?>

<style>
    #wpcontent {
        padding-left: 0 !important;
    }
</style>

<div class="container-fluid">
    <div>
        <h2><?= $service->langData['excluded_menu_name'] ?></h2>
    </div>

    <input type="hidden" id="get_rest_url" value="<?= get_rest_url( null, 'v1') ?>">

    <button type="button" id="calendarFormModalExcludedActivityButton" class="btn btn-primary" data-toggle="modal" data-target="#calendarFormModalExcludedActivity"><?= $service->langData['add_new'] ?></button>

    <div class="d-flex justify-content-center">
        <div class="modal fade" id="calendarFormModalExcludedActivity" tabindex="-1" role="dialog" aria-labelledby="calendarFormModalExcludedActivity" aria-hidden="true">
            <div class="modal-dialog modal modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-excluded-activity' ?>">
                        <div class="modal-body">
                            <div class="form-group my-plugin-form-group">
                                <label for="excluded_activity_name"><?= $service->langData['calendar_grid_data_field14_name'] ?></label>
                                <input type="text" class="form-control" id="excluded_activity_name" name="excluded_activity_name" required>
                                <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field14_description'] ?></small>
                                <div id="excluded_activity_form_group"></div>
                            </div>

                            <hr class="hr2">

                            <div class="form-group my-plugin-form-group">
                                <label for="excluded_activity_start_at"><?= $service->langData['calendar_grid_data_field15_name'] ?></label>
                                <input type="time" class="form-control" id="excluded_activity_start_at" name="excluded_activity_start_at" step="300" pattern="[0-9]{2}:[0-9]{2}" min="<?= $calendarService->calendar->get_first_hour_on_calendar() ?>" max="<?= $calendarService->calendar->get_last_hour_on_calendar() ?>">
                                <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field15_description'] ?></small>
                            </div>

                            <hr class="hr2">

                            <div class="form-group my-plugin-form-group">
                                <label for="excluded_activity_end_at"><?= $service->langData['calendar_grid_data_field16_name'] ?></label>
                                <input type="time" class="form-control" id="excluded_activity_end_at" name="excluded_activity_end_at" step="300" pattern="[0-9]{2}:[0-9]{2}" min="<?= $calendarService->calendar->get_first_hour_on_calendar() ?>" max="<?= $calendarService->calendar->get_last_hour_on_calendar() ?>">
                                <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field15_description'] ?></small>
                            </div>

                            <hr class="hr2">

                            <div class="form-group my-plugin-form-group">
                                <label for="excluded_activity_date"><?= $service->langData['calendar_grid_data_field18_name'] ?></label>
                                <input type="date" class="form-control" id="excluded_activity_date" name="excluded_activity_date" min="<?= date('Y-m-d'); ?>" required>
                            </div>

                            <hr class="hr2">

                            <div class="form-group my-plugin-form-group">
                                <label for="excluded_activity_bg_color"><?= $service->langData['calendar_grid_data_field17_name'] ?></label>
                                <input type="color" class="form-control" id="excluded_activity_bg_color" name="excluded_activity_bg_color" value="#ff0000">
                                <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field17_description'] ?></small>
                            </div>

                            <hr class="hr2">

                            <div class="form-group my-plugin-form-group">
                                <label for="excluded_activity_type"><?= $service->langData['calendar_grid_data_field19_name'] ?></label>
                                <select class="form-control" id="excluded_activity_type" name="excluded_activity_type">
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
                                <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field19_description'] ?></small>
                            </div>

                            <input type="hidden" name="excluded_activity_is_active" id="excluded_activity_is_active" value="1">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close btn btn-secondary" data-dismiss="modal"><?= $service->langData['form_cancel'] ?></button>
                            <button type="submit" class="btn btn-success"><?= $service->langData['save'] ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="calendarFormModalExcludedActivityRemove" tabindex="-1" role="dialog" aria-labelledby="calendarFormModalExcludedActivityRemove" aria-hidden="true">
            <div class="modal-dialog modal modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-excluded-activity' ?>">
                        <div class="modal-body">
                            <div class="text-center">
                                <h3><?= $service->langData['delete_question'] ?></h3>
                                <h4 id="excluded_activity_item_to_remove"></h4>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="excluded_activity_name" value="delete">
                                <div id="excluded_activity_form_group_2"></div>
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
                    <th scope="col">Id</th>
                    <th scope="col"><?= $service->langData['excluded_activity_name'] ?></th>
                    <th scope="col"><?= $service->langData['excluded_activity_time_start'] ?></th>
                    <th scope="col"><?= $service->langData['excluded_activity_time_end'] ?></th>
                    <th scope="col"><?= $service->langData['excluded_activity_date'] ?></th>
                    <th scope="col"><?= $service->langData['excluded_activity_is_active'] ?></th>
                    <th scope="col"><?= $service->langData['edit_label'] ?></th>
                    <th scope="col"><?= $service->langData['delete_label'] ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($pagination as $activity) {
                            
                            echo "<tr>";
                            echo "<td style='background-color:" . $activity->excludedBgColor . ";'>" . $activity->id . "</td>";
                            echo "<td>" . $activity->excludedName . "</td>";

                            if($activity->excludedStartAt === null && $activity->excludedEndAt !== null) {
                                echo "<td> --:-- </td><td>" . $activity->excludedEndAt . "</td>";
                            }
                            else if($activity->excludedStartAt !== null && $activity->excludedEndAt === null) {
                                echo "<td>" . $activity->excludedStartAt . "</td><td> --:-- </td>";
                            }
                            else {
                                $excludedTime = $service->langData['excluded_all_day_long'];
                                echo "<td>" . $excludedTime . "</td><td>" . $excludedTime . "</td>";
                            }

                            echo "<td>" . $activity->excludedDate . "</td>";
                            $checked = $activity->excludedIsActive === true ? "checked" : "";
                            $isActive = $activity->excludedIsActive === true ? "1" : "0";
                            echo "<td><input type='checkbox' class='calendar-excluded-activity-is-active' data-target='" . $activity->id . "' " . $checked . "></td>";
                            unset($activity->created_at);
                            unset($activity->deleted_at);
                            unset($activity->calendar_key_type);
                            $activity->excludedIsActive = $isActive;
                            $data = implode("|,|", (array) $activity);
                            echo '<td><button class="btn btn-sm btn-info btn-excluded-activity-update-action" type="button" data-target="' .  $data . '">' . $service->langData['edit'] . '</button></td>';
                            echo '<td><button class="btn btn-sm btn-danger btn-excluded-activity-delete-action" type="button" data-target="' .  $data . '">' . $service->langData['delete'] . '</button></td>';
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div><?= PaginationService::get_links() ?></div>
    </div>
</div>