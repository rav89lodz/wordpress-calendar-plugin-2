<?php

use CalendarPlugin\src\classes\consts\CalendarSort;
use CalendarPlugin\src\classes\consts\CalendarStatus;
use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\ReservationModel;
use CalendarPlugin\src\classes\services\LanguageService;
use CalendarPlugin\src\classes\services\PaginationService;
use CalendarPlugin\src\classes\services\ReservationService;
use CalendarPlugin\src\classes\services\SearchService;
use CalendarPlugin\src\classes\services\SessionService;
use CalendarPlugin\src\classes\services\SortService;
use CalendarPlugin\src\classes\services\ValidationService;

if(isset($_POST['users_reservation_description'])) {
    $validationService = new ValidationService;
    $data = $validationService->validate_data($_POST);
    $rService = new ReservationService($data);
}

$service = new LanguageService(['optionPage', 'adminMenu', 'reservationMenu', 'reservationFriendlyNames', 'searchBar']);
$reservation = new ReservationModel();
$reservation = $reservation->all(CalendarTypes::CALENDAR_NEW_RESERVATION, true);

SessionService::destroySessionSequenceForActivityReservation();

if(isset($_POST['activity_reservation_order_vector']) && isset($_POST['activity_reservation_order_by'])) {
    $_SESSION['activity_reservation_order_vector'] = $_POST['activity_reservation_order_vector'] === CalendarSort::ASC ? CalendarSort::DESC : CalendarSort::ASC;
    $_SESSION['activity_reservation_order_by'] = $_POST['activity_reservation_order_by'];
}

if(isset($_SESSION['activity_reservation_order_by']) && isset($_SESSION['activity_reservation_order_vector'])) {
    $order_vector = $_SESSION['activity_reservation_order_vector'];
    $reservation = SortService::sortBy($reservation, $_SESSION['activity_reservation_order_by'],  $_SESSION['activity_reservation_order_vector']);
}

if(isset($_POST['search_bar_input_serach'])) {
    $_SESSION['search_bar_input_activity_reservation'] = $_POST['search_bar_input_serach'];
    $_SESSION['search_bar_option_activity_reservation'] = $_POST['search_bar_option_serach'];
    $_SESSION['search_bar_field_activity_reservation'] = $_POST['search_bar_field_serach'];
}

if(isset($_SESSION['search_bar_input_activity_reservation']) && isset($_SESSION['search_bar_option_activity_reservation']) && isset($_SESSION['search_bar_field_activity_reservation'])) {
    $reservation = SearchService::search($reservation, $_SESSION['search_bar_field_activity_reservation'], $_SESSION['search_bar_option_activity_reservation'], $_SESSION['search_bar_input_activity_reservation']);
}

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

    <div class="my-plugin-search-bar">
        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-activity-reservation' ?>">
            <div style="float: left;">
                <select class="form-control my-plugin-search-bar-input" name="search_bar_field_serach" aria-label="Select field">
                    <option value="id" selected>Id</option>
                    <option value="userName"><?= $service->langData['user_name'] ?></option>
                    <option value="userEmail"><?= $service->langData['user_email'] ?></option>
                    <option value="reservationDate"><?= $service->langData['reservation_date'] ?></option>
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
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-activity-reservation' ?>">
                            <input type="hidden" name="activity_reservation_order_by" value="id">
                            <input type="hidden" name="activity_reservation_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;">Id</a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-activity-reservation' ?>">
                            <input type="hidden" name="activity_reservation_order_by" value="userName">
                            <input type="hidden" name="activity_reservation_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['user_name'] ?></a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-activity-reservation' ?>">
                            <input type="hidden" name="activity_reservation_order_by" value="userEmail">
                            <input type="hidden" name="activity_reservation_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['user_email'] ?></a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-activity-reservation' ?>">
                            <input type="hidden" name="activity_reservation_order_by" value="reservationDate">
                            <input type="hidden" name="activity_reservation_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['reservation_date'] ?></a>
                        </form>
                    </th>
                    <th scope="col"><?= $service->langData['reservation_time'] ?></th>
                    <th scope="col"><?= $service->langData['activity_name'] ?></th>
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

                            echo "<td>" . $user->reservationDate . "</td>";
                            echo "<td>" . $user->reservationTime . "</td>";

                            if(isset($activity->name)) {
                                echo "<td>" . $activity->name . "</td>";
                            }
                            else {
                                echo "<td>---</td>";
                            }
                            
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