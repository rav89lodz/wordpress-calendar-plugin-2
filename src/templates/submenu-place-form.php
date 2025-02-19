<?php

use CalendarPlugin\src\classes\consts\CalendarSort;
use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\PlaceModel;
use CalendarPlugin\src\classes\services\LanguageService;
use CalendarPlugin\src\classes\services\PaginationService;
use CalendarPlugin\src\classes\services\PlacesPageService;
use CalendarPlugin\src\classes\services\SearchService;
use CalendarPlugin\src\classes\services\SessionService;
use CalendarPlugin\src\classes\services\SortService;
use CalendarPlugin\src\classes\services\ValidationService;

if(isset($_POST['activity_place_description'])) {
    $validationService = new ValidationService;
    $data = $validationService->validate_data($_POST);
    $ppService = new PlacesPageService($data);
}

$service = new LanguageService(['optionPage', 'adminMenu', 'searchBar']);
$places = new PlaceModel();
$places = $places->all(CalendarTypes::CALENDAR_PLACE, true);

SessionService::destroySessionSequenceForActivityPlaces();

if(isset($_POST['activity_place_order_vector']) && isset($_POST['activity_place_order_by'])) {
    $_SESSION['activity_place_order_vector'] = $_POST['activity_place_order_vector'] === CalendarSort::ASC ? CalendarSort::DESC : CalendarSort::ASC;
    $_SESSION['activity_place_order_by'] = $_POST['activity_place_order_by'];
}

if(isset($_SESSION['activity_place_order_by']) && isset($_SESSION['activity_place_order_vector'])) {
    $order_vector = $_SESSION['activity_place_order_vector'];
    $places = SortService::sortBy($places, $_SESSION['activity_place_order_by'],  $_SESSION['activity_place_order_vector']);
}

if(isset($_POST['search_bar_input_serach'])) {
    $_SESSION['search_bar_input_activity_place'] = $_POST['search_bar_input_serach'];
    $_SESSION['search_bar_option_activity_place'] = $_POST['search_bar_option_serach'];
    $_SESSION['search_bar_field_activity_place'] = $_POST['search_bar_field_serach'];
}

if(isset($_SESSION['search_bar_input_activity_place']) && isset($_SESSION['search_bar_option_activity_place']) && isset($_SESSION['search_bar_field_activity_place'])) {
    $places = SearchService::search($places, $_SESSION['search_bar_field_activity_place'], $_SESSION['search_bar_option_activity_place'], $_SESSION['search_bar_input_activity_place']);
}

$pagination = PaginationService::paginate($places);

?>

<style>
    #wpcontent {
        padding-left: 0 !important;
    }
</style>

<div class="container-fluid">
    <div>
        <h2><?= $service->langData['activity_place_title'] ?></h2>
    </div>

    <button type="button" id="calendarFormModalAddPlaceButton" class="btn btn-primary" data-toggle="modal" data-target="#calendarFormModalAddPlace"><?= $service->langData['add_new'] ?></button>

    <div class="my-plugin-search-bar">
        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-place' ?>">
            <div style="float: left;">
                <select class="form-control my-plugin-search-bar-input" name="search_bar_field_serach" aria-label="Select field">
                    <option value="id" selected>Id</option>
                    <option value="name"><?= $service->langData['title_label_places'] ?></option>
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
        <div class="modal fade" id="calendarFormModalAddPlace" tabindex="-1" role="dialog" aria-labelledby="calendarFormModalAddPlace" aria-hidden="true">
            <div class="modal-dialog modal modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-place' ?>">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="activity_place_description"><?= $service->langData['activity_place_field1_name'] ?></label>
                                <input type="text" class="form-control" id="activity_place_description" name="activity_place_description">
                                <small class="text-muted w-100 admin-field-text-small"><?= $service->langData['activity_place_field1_description'] ?></small>
                                <div id="activity_place_form_group"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close btn btn-secondary" data-dismiss="modal"><?= $service->langData['form_cancel'] ?></button>
                            <button type="submit" class="btn btn-success"><?= $service->langData['save'] ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="calendarFormModalRemovePlace" tabindex="-1" role="dialog" aria-labelledby="calendarFormModalRemovePlace" aria-hidden="true">
            <div class="modal-dialog modal modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-place' ?>">
                        <div class="modal-body">
                            <div class="text-center">
                                <h3><?= $service->langData['delete_question'] ?></h3>
                                <h4 id="plugin_place_item_to_remove"></h4>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="activity_place_description" value="delete">
                                <div id="activity_place_form_group_2"></div>
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
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-place' ?>">
                            <input type="hidden" name="activity_place_order_by" value="id">
                            <input type="hidden" name="activity_place_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;">Id</a>
                        </form>
                    </th>
                    <th scope="col">
                        <form method="POST" action="<?= site_url() . '/wp-admin/admin.php?page=calendar-plugin-submenu-place' ?>">
                            <input type="hidden" name="activity_place_order_by" value="name">
                            <input type="hidden" name="activity_place_order_vector" value="<?= $order_vector ?>">
                            <a href="" aria-label="submit form link" onclick="this.closest('form').submit();return false;"><?= $service->langData['title_label_places'] ?></a>
                        </form>
                    </th>
                    <th scope="col">[short_code] <i class="fas fa-question-circle" tabindex="0" data-bs-toggle="tooltip" title="<?= $service->langData['short_code_activity_place'] ?>"></i></th>
                    <th scope="col"><?= $service->langData['edit_label'] ?></th>
                    <th scope="col"><?= $service->langData['delete_label'] ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($pagination as $place) {
                            echo "<tr>";
                            echo "<td>" . $place->id . "</td>";
                            echo "<td>" . $place->name . "</td>";
                            echo "<td class='my-copy-short-code'><span style='padding-right: 10px;'>[" . $place->shortCode . "]</span><i class='fa-regular fa-copy'></i></td>";
                            echo '<td><button class="btn btn-sm btn-info btn-place-update-action" type="button">' . $service->langData['edit'] . '</button></td>';
                            echo '<td><button class="btn btn-sm btn-danger btn-place-delete-action" type="button">' . $service->langData['delete'] . '</button></td>';
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div><?= PaginationService::get_links() ?></div>
    </div>
</div>