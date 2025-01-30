<?php

use CalendarPlugin\src\classes\services\CalendarService;
use CalendarPlugin\src\classes\services\LanguageService;

$service = new LanguageService('modalFormFriendlyNames');
$calendarService = new CalendarService();

$script = null;
if($calendarService->calendar->get_calendar_captcha_site_key() !== null && ! isset($_SERVER['recaptcha_on'])) {
    $script = "https://www.google.com/recaptcha/api.js?render=" . $calendarService->calendar->get_calendar_captcha_site_key();
    $_SERVER['recaptcha_on'] = "script run";
}

if($calendarService->calendar->get_calendar_captcha_site_key() === null && isset($_SERVER['recaptcha_on'])) {
    unset($_SERVER['recaptcha_on']);
}

?>

<script type='text/javascript' src="<?= $script ?>"></script>

<input type="hidden" id="get_rest_url2" value="<?= get_rest_url(null, 'v1') ?>">

<div class="alert alert-success text-center my-alert-success2" role="alert">
    <h4 class="alert-heading" id="form_success2"></h4>
</div>
<div class="alert alert-danger text-center my-alert-error2" role="alert">
    <h4 class="alert-heading" id="form_error2"></h4>
</div>

<button type="button" id="calendarFormModalAddActivityButton" class="btn btn-primary" data-toggle="modal" data-target="#calendarFormModalAddActivity"><?= $service->langData['add_activity_active_button'] ?></button>

<div class="d-flex justify-content-center">
    <div class="modal fade" id="calendarFormModalAddActivity" tabindex="-1" role="dialog" aria-labelledby="calendarFormModalAddActivityTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row w-100">
                        <div class="col-10">
                            <h5 class="modal-title"><?= $service->langData['add_activity_title'] ?></h5>
                        </div>
                        <div class="col-2">
                            <div class="modal-plugin-close-button2">
                                <button type="button" class="close btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" data-dismiss="modal">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="calendar_modal_form_add_activity">
                    <div class="modal-body">
                        <div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon76"><?= $service->langData['user_name_calendar_add_activity'] ?></span>
                                </div>
                                <input type="text" class="form-control" name="user_name_calendar_add_activity" aria-describedby="basic-addon76" required>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon77"><?= $service->langData['user_email_calendar_add_activity'] ?></span>
                                </div>
                                <input type="email" class="form-control" name="user_email_calendar_add_activity" aria-describedby="basic-addon77" required>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon78"><?= $service->langData['user_phone_calendar_add_activity'] ?></span>
                                </div>
                                <input type="tel" class="form-control" name="user_phone_calendar_add_activity" aria-describedby="basic-addon78" required>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon79"><?= $service->langData['date_calendar_add_activity'] ?></span>
                                </div>
                                <input type="text" class="form-control" name="date_calendar_add_activity" aria-describedby="basic-addon79" required>
                                <small class="text-muted w-100"><?= $service->langData['date_calendar_add_activity_text_muted'] ?></small>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon80"><?= $service->langData['time_start_calendar_add_activity'] ?></span>
                                </div>
                                <input type="time" step="300" class="form-control" name="time_start_calendar_add_activity" aria-describedby="basic-addon80" required value="12:00">
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon81"><?= $service->langData['time_end_calendar_add_activity'] ?></span>
                                </div>
                                <input type="time" step="300" class="form-control" name="time_end_calendar_add_activity" aria-describedby="basic-addon81" required value="13:00">
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon82"><?= $service->langData['name_calendar_add_activity'] ?></span>
                                </div>
                                <input type="text" class="form-control" name="name_calendar_add_activity" aria-describedby="basic-addon82" required>
                            </div>

                            <input type="hidden" name="recaptcha_response_contact" id="recaptcha_response_contact">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="close btn btn-secondary" data-dismiss="modal"><?= $service->langData['cancel'] ?></button>
                        <button type="button" id="submit_calendar_modal_form_add_activity" data-target="<?= $calendarService->calendar->get_calendar_captcha_site_key() ?>" class="btn btn-success g-recaptcha"><?= $service->langData['send'] ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
