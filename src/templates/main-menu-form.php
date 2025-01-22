<?php

use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\MainSettingsModel;
use CalendarPlugin\src\classes\services\LanguageService;

$service = new LanguageService(['optionPage', 'adminMenu']);

$model = new MainSettingsModel();
$model = $model->get(CalendarTypes::CALENDAR_OPTION);

?>
<style>
    #wpcontent {
        padding-left: 0 !important;
    }
</style>

<input type="hidden" id="get_rest_url" value="<?= get_rest_url( null, 'v1') ?>">

<div class="container-fluid">
    <div>
        <h2><?= $service->langData['main_menu_settings'] ?></h2>
    </div>

    <div class="alert alert-success text-center my-alert-success" role="alert">
        <h4 class="alert-heading" id="form_success"></h4>
    </div>

    <div class="alert alert-danger text-center my-alert-error" role="alert">
        <h4 class="alert-heading" id="form_error"></h4>
    </div>

    <div class="card" style="max-width: 90%">
        <div>
            <div>
                <h3>[calendar-grid1]</h3>
                <p><?= $service->langData['main_short_code'] ?></p>
            </div>
            <div>
                <h3>[contact-form-calendar1]</h3>
                <p><?= $service->langData['main_short_code_form'] ?></p>
            </div>
        </div>

        <hr class="hr1">

        <div>
            <form id="calendar_form_main_menu">
                <div>
                    <div class="form-check admin-field-mb">
                        <div class="admin-check-input">
                            <input class="form-check-input admin-field-checkbox" type="checkbox" value="1" id="calendar_plugin_one_day_view" name="calendar_plugin_one_day_view"
                                <?= $model !== null && isset($model->oneDayView) && boolval($model->oneDayView) === true ? 'checked': '' ?>>
                            <input type='hidden' value='0' name='calendar_plugin_one_day_view'>
                            <label class="form-check-label" for="calendar_plugin_one_day_view"><?= $service->langData['main_menu_field19_name'] ?></label>
                        </div>
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field19_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-check admin-field-mb" id="calendar_plugin_horizontal_calendar_grid_div">
                        <div class="admin-check-input">
                            <input class="form-check-input admin-field-checkbox" type="checkbox" value="1" id="calendar_plugin_horizontal_calendar_grid"
                                name="calendar_plugin_horizontal_calendar_grid" <?= $model !== null && isset($model->horizontalCalendarGrid) && boolval($model->horizontalCalendarGrid) === true ? 'checked': '' ?>>
                            <input type='hidden' value='0' name='calendar_plugin_horizontal_calendar_grid'>
                            <label class="form-check-label" for="calendar_plugin_horizontal_calendar_grid"><?= $service->langData['main_menu_field13_name'] ?></label>
                        </div>
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field13_description'] ?></small>
                    </div>

                    <hr class="hr2" id="calendar_plugin_horizontal_calendar_grid_hr">

                    <div class="form-group">
                        <label for="calendar_plugin_cell_min_height"><?= $service->langData['main_menu_field18_name'] ?></label>
                        <input type="number" class="form-control" id="calendar_plugin_cell_min_height" name="calendar_plugin_cell_min_height" value="<?= $model !== null && isset($model->cellMinHeight) ? $model->cellMinHeight : null ?>">
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field18_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-check admin-field-mb">
                        <div class="admin-check-input">
                            <input class="form-check-input admin-field-checkbox" type="checkbox" value="1" id="calendar_plugin_add_scroll_to_table"
                                name="calendar_plugin_add_scroll_to_table" <?= $model !== null && isset($model->addScrollToTable) && boolval($model->addScrollToTable) === true ? 'checked': '' ?>>
                            <input type='hidden' value='0' name='calendar_plugin_add_scroll_to_table'>
                            <label class="form-check-label" for="calendar_plugin_add_scroll_to_table"><?= $service->langData['main_menu_field15_name'] ?></label>
                        </div>
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field15_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-group">
                        <label for="calendar_plugin_grid_width"><?= $service->langData['main_menu_field16_name'] ?></label>
                        <input type="number" class="form-control" id="calendar_plugin_grid_width" name="calendar_plugin_grid_width" value="<?= $model !== null && isset($model->gridWidth) ? $model->gridWidth : null ?>">
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field16_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-group">
                        <label for="calendar_plugin_grid_height"><?= $service->langData['main_menu_field17_name'] ?></label>
                        <input type="number" class="form-control" id="calendar_plugin_grid_height" name="calendar_plugin_grid_height" value="<?= $model !== null && isset($model->gridHeight) ? $model->gridHeight : null ?>">
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field17_description'] ?></small>
                    </div>

                    <hr class="hr2">
                    
                    <div class="form-check admin-field-mb">
                        <div class="admin-check-input">
                            <input class="form-check-input admin-field-checkbox" type="checkbox" value="1" id="calendar_plugin_make_rsv_by_calendar"
                                name="calendar_plugin_make_rsv_by_calendar" <?= $model !== null && isset($model->makeRsvByCalendar) && boolval($model->makeRsvByCalendar) === true ? 'checked': '' ?>>
                            <input type='hidden' value='0' name='calendar_plugin_make_rsv_by_calendar'>
                            <label class="form-check-label" for="calendar_plugin_make_rsv_by_calendar"><?= $service->langData['main_menu_field1_name'] ?></label>
                        </div>
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field1_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-check admin-field-mb">
                        <div class="admin-check-input">
                            <input class="form-check-input admin-field-checkbox" type="checkbox" value="1" id="calendar_plugin_fluent_calendar_grid"
                                name="calendar_plugin_fluent_calendar_grid" <?= $model !== null && isset($model->fluentCalendarGrid) && boolval($model->fluentCalendarGrid) === true ? 'checked': '' ?>>
                            <input type='hidden' value='0' name='calendar_plugin_fluent_calendar_grid'>
                            <label class="form-check-label" for="calendar_plugin_fluent_calendar_grid"><?= $service->langData['main_menu_field2_name'] ?></label>
                        </div>
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field2_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-check admin-field-mb">
                        <div class="admin-check-input">
                            <input class="form-check-input admin-field-checkbox" type="checkbox" value="1" id="calendar_plugin_duration_time_on_grid"
                                name="calendar_plugin_duration_time_on_grid" <?= $model !== null && isset($model->durationTimeOnGrid) && boolval($model->durationTimeOnGrid) === true ? 'checked': '' ?>>
                            <input type='hidden' value='0' name='calendar_plugin_duration_time_on_grid'>
                            <label class="form-check-label" for="calendar_plugin_duration_time_on_grid"><?= $service->langData['main_menu_field3_name'] ?></label>
                        </div>
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field3_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-check admin-field-mb">
                        <div class="admin-check-input">
                            <input class="form-check-input admin-field-checkbox" type="checkbox" value="1" id="calendar_plugin_activity_place_on_grid"
                                name="calendar_plugin_activity_place_on_grid" <?= $model !== null && isset($model->activityPlaceOnGrid) && boolval($model->activityPlaceOnGrid) === true ? 'checked': '' ?>>
                            <input type='hidden' value='0' name='calendar_plugin_activity_place_on_grid'>
                            <label class="form-check-label" for="calendar_plugin_activity_place_on_grid"><?= $service->langData['main_menu_field12_name'] ?></label>
                        </div>
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field12_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-check admin-field-mb">
                        <div class="admin-check-input">
                            <input class="form-check-input admin-field-checkbox" type="checkbox" value="1" id="calendar_plugin_start_time_on_grid"
                                name="calendar_plugin_start_time_on_grid" <?= $model !== null && isset($model->startTimeOnGrid) && boolval($model->startTimeOnGrid) === true ? 'checked': '' ?>>
                            <input type='hidden' value='0' name='calendar_plugin_start_time_on_grid'>
                            <label class="form-check-label" for="calendar_plugin_start_time_on_grid"><?= $service->langData['main_menu_field20_name'] ?></label>
                        </div>
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field20_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-check admin-field-mb">
                        <div class="admin-check-input">
                            <input class="form-check-input admin-field-checkbox" type="checkbox" value="1" id="calendar_plugin_end_time_on_grid"
                                name="calendar_plugin_end_time_on_grid" <?= $model !== null && isset($model->endTimeOnGrid) && boolval($model->endTimeOnGrid) === true ? 'checked': '' ?>>
                            <input type='hidden' value='0' name='calendar_plugin_end_time_on_grid'>
                            <label class="form-check-label" for="calendar_plugin_end_time_on_grid"><?= $service->langData['main_menu_field4_name'] ?></label>
                        </div>
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field4_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-group">
                        <label for="calendar_plugin_recipients"><?= $service->langData['main_menu_field5_name'] ?></label>
                        <input type="email" class="form-control" id="calendar_plugin_recipients" name="calendar_plugin_recipients" placeholder="email@email.com" value="<?= $model !== null && isset($model->recipients) ? $model->recipients : null ?>">
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field5_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-group">
                        <label for="calendar_plugin_message_success"><?= $service->langData['main_menu_field6_name'] ?></label>
                        <textarea class="form-control" id="calendar_plugin_message_success" name="calendar_plugin_message_success" rows="3" placeholder="<?= $service->langData['main_menu_textarea_field_placeholder'] ?>"><?= $model !== null && isset($model->messageSuccess) ? $model->messageSuccess : null ?></textarea>
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field6_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-group">
                        <label for="calendar_plugin_message_error"><?= $service->langData['main_menu_field7_name'] ?></label>
                        <textarea class="form-control" id="calendar_plugin_message_error" name="calendar_plugin_message_error" rows="3" placeholder="<?= $service->langData['main_menu_textarea_field_placeholder'] ?>"><?= $model !== null && isset($model->messageError) ? $model->messageError : null ?></textarea>
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field7_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-group">
                        <label for="calendar_plugin_reservation_send_message"><?= $service->langData['main_menu_field8_name'] ?></label>
                        <textarea class="form-control" id="calendar_plugin_reservation_send_message" name="calendar_plugin_reservation_send_message" rows="3" placeholder="<?= $service->langData['main_menu_textarea_field_placeholder'] ?>"><?= $model !== null && isset($model->reservationSendMessage) ? $model->reservationSendMessage : null ?></textarea>
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field8_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-group">
                        <label for="calendar_plugin_start_at"><?= $service->langData['main_menu_field9_name'] ?></label>
                        <select class="form-control" id="calendar_plugin_start_at" name="calendar_plugin_start_at" required>
                            <?php
                                for($i = 0; $i < 24; $i++) {
                                    $toSet = $i . ":00";
                                    if($i < 10) {
                                        $toSet = "0" . $toSet;
                                    }

                                    if($model !== null && isset($model->startAt) && $model->startAt == $toSet) {
                                        echo '<option value="'. $toSet .'" selected>' . $toSet . '</option>';
                                    }
                                    else {
                                        echo '<option value="'. $toSet .'">' . $toSet . '</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>

                    <hr class="hr2">

                    <div class="form-group">
                        <label for="calendar_plugin_end_at"><?= $service->langData['main_menu_field10_name'] ?></label>
                        <select class="form-control" id="calendar_plugin_end_at" name="calendar_plugin_end_at" required>
                            <?php
                                for($i = 0; $i < 24; $i++) {
                                    $toSet = $i . ":00";
                                    if($i < 10) {
                                        $toSet = "0" . $toSet;
                                    }

                                    if($model !== null && isset($model->endAt) && $model->endAt == $toSet) {
                                        echo '<option value="'. $toSet .'" selected>' . $toSet . '</option>';
                                    }
                                    else {
                                        echo '<option value="'. $toSet .'">' . $toSet . '</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>

                    <hr class="hr2">

                    <div class="form-group">
                        <label for="calendar_plugin_interval"><?= $service->langData['main_menu_field11_name'] ?></label>
                        <select class="form-control" id="calendar_plugin_interval" name="calendar_plugin_interval" required>
                            <?php
                                if($model !== null && isset($model->interval) && $model->interval == "15") {
                                    echo '<option value="15" selected>00:15</option><option value="30">00:30</option><option value="60">01:00</option>';
                                }
                                else if($model !== null && isset($model->interval) && $model->interval == "30") {
                                    echo '<option value="15">00:15</option><option value="30" selected>00:30</option><option value="60">01:00</option>';
                                }
                                else {
                                    echo '<option value="15">00:15</option><option value="30">00:30</option><option value="60" selected>01:00</option>';
                                }
                            ?>
                        </select>
                    </div>

                    <hr class="hr2">

                    <div class="form-group">
                        <label for="calendar_plugin_captcha_site_key"><?= $service->langData['main_menu_field21_name'] ?></label>
                        <input type="text" class="form-control" id="calendar_plugin_captcha_site_key" name="calendar_plugin_captcha_site_key" value="<?= $model !== null && isset($model->captchaSiteKey) ? $model->captchaSiteKey : null ?>">
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field21_description'] ?></small>
                    </div>

                    <hr class="hr2">

                    <div class="form-group">
                        <label for="calendar_plugin_captcha_secret_key"><?= $service->langData['main_menu_field22_name'] ?></label>
                        <input type="text" class="form-control" id="calendar_plugin_captcha_secret_key" name="calendar_plugin_captcha_secret_key" value="<?= $model !== null && isset($model->captchaSecretKey) ? $model->captchaSecretKey : null ?>">
                        <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['main_menu_field22_description'] ?></small>
                    </div>

                </div>
                <div class="admin-menu-button">
                    <button type="button" id="submit_calendar_form_main_menu" class="button button-primary button-large"><?= $service->langData['save'] ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

