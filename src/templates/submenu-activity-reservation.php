<?php

use CalendarPlugin\src\classes\consts\CalendarStatus;
use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\ReservationModel;
use CalendarPlugin\src\classes\services\LanguageService;
use CalendarPlugin\src\classes\services\PaginationService;
use CalendarPlugin\src\classes\services\ReservationService;
use CalendarPlugin\src\classes\services\ValidationService;

if(isset($_POST['users_reservation_description'])) {
    $validationService = new ValidationService;
    $data = $validationService->validate_data($_POST);
    $rService = new ReservationService($data);
}

$service = new LanguageService(['optionPage', 'adminMenu', 'reservationMenu', 'reservationFriendlyNames']);
$reservation = new ReservationModel();
$reservation = $reservation->all(CalendarTypes::CALENDAR_NEW_RESERVATION, true);
$pagination = PaginationService::paginate($reservation);

?>

<style>
    #wpcontent {
        padding-left: 0 !important;
    }
</style>

<div class="container-fluid">
    <div>
        <h2><?= $service->langData['reservation_menu_description'] ?></h2>
    </div>

    <div class="d-flex justify-content-center">
        <div class="modal fade" id="calendarFormModalUpdateUsersReservation" tabindex="-1" role="dialog" aria-labelledby="calendarFormModalUpdateUsersReservation" aria-hidden="true">
            <div class="modal-dialog modal modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-activity-reservation' ?>">
                        <div class="modal-body">
                            <div class="text-center">
                                <h4 id="user_reservation_item_to_update"></h4>
                            </div>

                            <div class="form-group my-plugin-form-group">
                                <label for="calendar_status"><?= $service->langData['calendar_grid_data_field10_name'] ?></label>
                                <select class="form-control" id="calendar_status" name="calendar_status" required>
                                    <option value="<?= CalendarStatus::CALENDAR_STATUS_ACCEPTED ?>"><?= $service->langData['status_accepted'] ?></option>
                                    <option value="<?= CalendarStatus::CALENDAR_STATUS_REJECTED ?>" selected><?= $service->langData['status_rejected'] ?></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="users_reservation_description" value="delete">
                                <div id="users_reservation_form_group"></div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="close btn btn-info" data-dismiss="modal"><?= $service->langData['form_cancel'] ?></button>
                            <button type="submit" class="btn btn-success"><?= $service->langData['save'] ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="calendarFormModalUsersReservation" tabindex="-1" role="dialog" aria-labelledby="calendarFormModalUsersReservation" aria-hidden="true">
            <div class="modal-dialog modal modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-activity-reservation' ?>">
                        <div class="modal-body">
                            <div class="text-center">
                                <h3><?= $service->langData['delete_question'] ?></h3>
                                <h4 id="user_reservation_item_to_remove"></h4>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="users_reservation_description" value="delete">
                                <div id="users_reservation_form_group_2"></div>
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
                    <th scope="col"><?= $service->langData['user_name'] ?></th>
                    <th scope="col"><?= $service->langData['user_email'] ?></th>
                    <th scope="col"><?= $service->langData['activity_name'] ?></th>
                    <th scope="col"><?= $service->langData['reservation_date'] ?></th>
                    <th scope="col"><?= $service->langData['reservation_time'] ?></th>
                    <th scope="col"><?= $service->langData['change_status_label'] ?></th>
                    <th scope="col"><?= $service->langData['delete_label'] ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($pagination as $user) {
                            $activity = $user->activity;

                            $bgColor = "#ff0000";
                            if(isset($user->reservationStatus) && $user->reservationStatus === CalendarStatus::CALENDAR_STATUS_ACCEPTED) {
                                $bgColor = "#13c70a";
                            }

                            echo "<tr>";
                            echo "<td style='background-color: " . $bgColor .";'>" . $user->id . "</td>";
                            echo "<td>" . $user->userName . "</td>";
                            echo "<td class='my-copy-short-code'><span style='padding-right: 10px;'>" . $user->userEmail . "</span><i class='fa-regular fa-copy'></i></td>";

                            if(isset($activity->name)) {
                                echo "<td>" . $activity->name . "</td>";
                            }
                            else {
                                echo "<td>---</td>";
                            }

                            echo "<td>" . $user->reservationDate . "</td>";
                            echo "<td>" . $user->reservationTime . "</td>";
                            $dataTarget = implode('|>|', [$user->id, $user->userName, $user->userEmail, $user->reservationStatus]);
                            echo '<td><button class="btn btn-sm btn-info btn-user-reservation-update-action" data-target="' .  $dataTarget .'" type="button">' . $service->langData['change_status'] . '</button></td>';
                            echo '<td><button class="btn btn-sm btn-danger btn-user-reservation-delete-action" type="button" data-target="' . $dataTarget  . '">' . $service->langData['delete'] . '</button></td>';
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div><?= PaginationService::get_links() ?></div>
    </div>
</div>