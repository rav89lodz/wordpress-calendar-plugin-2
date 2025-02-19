<?php

use CalendarPlugin\src\classes\consts\CalendarSort;
use CalendarPlugin\src\classes\consts\CalendarStatus;
use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\FormValidator;
use CalendarPlugin\src\classes\models\AddGridActivityModel;
use CalendarPlugin\src\classes\services\LanguageService;
use CalendarPlugin\src\classes\services\PaginationService;
use CalendarPlugin\src\classes\services\AddGridActivityService;
use CalendarPlugin\src\classes\services\SearchService;
use CalendarPlugin\src\classes\services\SessionService;
use CalendarPlugin\src\classes\services\SortService;
use CalendarPlugin\src\classes\services\ValidationService;

if(isset($_POST['new_grid_activity_description'])) {
    $validationService = new ValidationService;
    $data = $validationService->validate_data($_POST);
    $agaService = new AddGridActivityService($data);
}

$service = new LanguageService(['optionPage', 'adminMenu', 'addActivityMenu', 'addActivityFriendlyNames', 'days', 'searchBar']);

$gridActivities = new AddGridActivityModel();
$places = $gridActivities->all(CalendarTypes::CALENDAR_PLACE, true);

SessionService::destroySessionSequenceForGridReservation();

$gridActivities = $gridActivities->all(CalendarTypes::CALENDAR_ADD_GRID_ACTIVITY, true);

if(isset($_POST['reservation_order_vector']) && isset($_POST['reservation_order_by'])) {
    $_SESSION['reservation_order_vector'] = $_POST['reservation_order_vector'] === CalendarSort::ASC ? CalendarSort::DESC : CalendarSort::ASC;
    $_SESSION['reservation_order_by'] = $_POST['reservation_order_by'];
}

if(isset($_SESSION['reservation_order_by']) && isset($_SESSION['reservation_order_vector'])) {
    $order_vector = $_SESSION['reservation_order_vector'];
    $gridActivities = SortService::sortBy($gridActivities, $_SESSION['reservation_order_by'],  $_SESSION['reservation_order_vector']);
}

if(isset($_POST['search_bar_input_serach'])) {
    $_SESSION['search_bar_input_reservation'] = $_POST['search_bar_input_serach'];
    $_SESSION['search_bar_option_reservation'] = $_POST['search_bar_option_serach'];
    $_SESSION['search_bar_field_reservation'] = $_POST['search_bar_field_serach'];
}

if(isset($_SESSION['search_bar_input_reservation']) && isset($_SESSION['search_bar_option_reservation']) && isset($_SESSION['search_bar_field_reservation'])) {
    $gridActivities = SearchService::search($gridActivities, $_SESSION['search_bar_field_reservation'], $_SESSION['search_bar_option_reservation'], $_SESSION['search_bar_input_reservation']);
}

$pagination = PaginationService::paginate($gridActivities);

$formValidator = new FormValidator;

?>

<style>
    #wpcontent {
        padding-left: 0 !important;
    }
</style>

<div class="container-fluid">
    <div>
        <h2><?= $service->langData['activity_menu_description'] ?></h2>
    </div>

    <div class="my-plugin-search-bar">
        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid-reservation' ?>">
            <div style="float: left;">
                <select class="form-control my-plugin-search-bar-input" name="search_bar_field_serach" aria-label="Select field">
                    <option value="id" selected>Id</option>
                    <option value="activityName"><?= $service->langData['add_activity_name'] ?></option>
                    <option value="activityUserName"><?= $service->langData['add_activity_user_name'] ?></option>
                    <option value="activityUserEmail"><?= $service->langData['add_activity_user_email'] ?></option>
                    <option value="activityUserPhone"><?= $service->langData['add_activity_user_phone'] ?></option>
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

    <div class="m-5"></div>

    <div class="d-flex justify-content-center">
        <div class="modal fade" id="calendarFormModalAddGridActivity" tabindex="-1" role="dialog" aria-labelledby="calendarFormModalAddGridActivity" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid-reservation' ?>">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="activity_name"><?= $service->langData['calendar_grid_data_field1_name'] ?></label>
                                        <input type="text" class="form-control" id="activity_name" name="activity_name" required>
                                        <input type="hidden" name="new_grid_activity_description" value="update">
                                        <div id="new_grid_activity_form_group"></div>
                                    </div>

                                    <hr class="hr2">

                                    <div class="form-group">
                                        <label for="activity_start_at"><?= $service->langData['calendar_grid_data_field2_name'] ?></label>
                                        <input type="time" class="form-control" id="activity_start_at" name="activity_start_at" step="300" pattern="[0-9]{2}:[0-9]{2}" value="12:00" required>
                                    </div>

                                    <hr class="hr2">

                                    <div class="form-group">
                                        <label for="activity_end_at"><?= $service->langData['calendar_grid_data_field3_name'] ?></label>
                                        <input type="time" class="form-control" id="activity_end_at" name="activity_end_at" step="300" pattern="[0-9]{2}:[0-9]{2}" value="13:00" required>
                                    </div>

                                    <hr class="hr2">

                                    <div class="form-group my-plugin-form-group">
                                        <label for="activity_bg_color"><?= $service->langData['calendar_grid_data_field7_name'] ?></label>
                                        <input type="color" class="form-control" id="activity_bg_color" name="activity_bg_color" value="#ff0000" required>
                                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field7_description'] ?></small>
                                    </div>

                                    <hr class="hr2">

                                    <div class="form-group my-plugin-form-group">
                                        <label for="activity_type"><?= $service->langData['calendar_grid_data_field8_name'] ?></label>
                                        <select class="form-control" id="activity_type" name="activity_type" required>
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
                                    </div>

                                    <hr class="hr2">

                                    <div class="form-group my-plugin-form-group">
                                        <label for="activity_slot"><?= $service->langData['calendar_grid_data_field9_name'] ?></label>
                                        <input type="number" class="form-control" id="activity_slot" name="activity_slot" min="1" value="1" required>
                                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['calendar_grid_data_field9_description'] ?></small>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group my-plugin-form-group">
                                        <label for="calendar_status"><?= $service->langData['calendar_grid_data_field10_name'] ?></label>
                                        <select class="form-control" id="calendar_status" name="calendar_status" required>
                                            <option value="<?= CalendarStatus::CALENDAR_STATUS_ACCEPTED ?>" selected><?= $service->langData['status_accepted'] ?></option>
                                            <option value="<?= CalendarStatus::CALENDAR_STATUS_REJECTED ?>"><?= $service->langData['status_rejected'] ?></option>
                                        </select>
                                    </div>

                                    <hr class="hr2">

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

        <div class="modal fade" id="calendarFormModalRemoveGridActivity" tabindex="-1" role="dialog" aria-labelledby="calendarFormModalRemoveGridActivity" aria-hidden="true">
            <div class="modal-dialog modal modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid-reservation' ?>">
                        <div class="modal-body">
                            <div class="text-center">
                                <h3><?= $service->langData['delete_question'] ?></h3>
                                <h4 id="new_grid_activity_item_to_remove"></h4>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="new_grid_activity_description" value="delete">
                                <div id="new_grid_activity_form_group_2"></div>
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
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid-reservation' ?>">
                            <input type="hidden" name="reservation_order_by" value="id">
                            <input type="hidden" name="reservation_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;">Id</a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid-reservation' ?>">
                            <input type="hidden" name="reservation_order_by" value="activityName">
                            <input type="hidden" name="reservation_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['add_activity_name'] ?></a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid-reservation' ?>">
                            <input type="hidden" name="reservation_order_by" value="activityUserName">
                            <input type="hidden" name="reservation_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['add_activity_user_name'] ?></a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid-reservation' ?>">
                            <input type="hidden" name="reservation_order_by" value="activityUserEmail">
                            <input type="hidden" name="reservation_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['add_activity_user_email'] ?></a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-grid-reservation' ?>">
                            <input type="hidden" name="reservation_order_by" value="activityUserPhone">
                            <input type="hidden" name="reservation_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['add_activity_user_phone'] ?></a>
                        </form>
                    </th>
                    <th scope="col"><?= $service->langData['add_activity_time_start'] ?></th>
                    <th scope="col"><?= $service->langData['add_activity_time_end'] ?></th>
                    <th scope="col"><?= $service->langData['grid_activity_days'] ?></th>
                    <th scope="col"><?= $service->langData['change_status_label'] ?></th>
                    <th scope="col"><?= $service->langData['delete_label'] ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($pagination as $activity) {
                            $bgColor = "#ff0000";
                            if(isset($activity->activityStatus) && $activity->activityStatus === CalendarStatus::CALENDAR_STATUS_ACCEPTED) {
                                $bgColor = "#13c70a";
                            }
                            echo "<tr>";
                            echo "<td style='background-color: " . $bgColor .";'>" . $activity->id . "</td>";
                            echo "<td>" . $activity->activityName . "</td>";
                            echo "<td>" . $activity->activityUserName . "</td>";
                            echo "<td class='my-copy-short-code'><span style='padding-right: 10px;'>" . $activity->activityUserEmail . "</span><i class='fa-regular fa-copy'></i></td>";
                            echo "<td>" . $activity->activityUserPhone . "</td>";
                            echo "<td>" . $activity->activityTimeStart . "</td>";
                            echo "<td>" . $activity->activityTimeEnd . "</td>";
                            echo "<td>" . $activity->activityDate . "</td>";

                            $isCyclic = "1";
                            if($formValidator->is_valid_date_2($activity->activityDate)) {
                                $isCyclic = "0";
                                $activity->activityDate = date('Y-m-d', strtotime($activity->activityDate));
                            }

                            $dataTarget = implode('|>|', [$activity->id, $activity->activityName, $activity->activityUserName, $activity->activityTimeStart, $activity->activityTimeEnd, $activity->activityDate, $isCyclic, $activity->activityStatus]);
                            echo '<td><button class="btn btn-sm btn-info btn-add-new-grid-update-action" data-target="' .  $dataTarget .'" type="button">' . $service->langData['change_status'] . '</button></td>';
                            echo '<td><button class="btn btn-sm btn-danger btn-add-new-grid-delete-action" data-target="' .  $dataTarget .'" type="button">' . $service->langData['delete'] . '</button></td>';
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div><?= PaginationService::get_links() ?></div>
    </div>
</div>