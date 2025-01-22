<?php

use CalendarPlugin\src\classes\consts\CalendarTypes;
use CalendarPlugin\src\classes\models\PlaceModel;
use CalendarPlugin\src\classes\services\LanguageService;
use CalendarPlugin\src\classes\services\PaginationService;
use CalendarPlugin\src\classes\services\PlacesPageService;
use CalendarPlugin\src\classes\services\ValidationService;

if(isset($_POST['activity_place_description'])) {
    $validationService = new ValidationService;
    $data = $validationService->validate_data($_POST);
    $ppService = new PlacesPageService($data);
}

$service = new LanguageService(['optionPage', 'adminMenu']);
$places = new PlaceModel();
$places = $places->all(CalendarTypes::CALENDAR_PLACE, true);
$pagination = PaginationService::paginate($places);

// global $shortcode_tags;
// var_dump($shortcode_tags);
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
                    <th scope="col">Id</th>
                    <th scope="col"><?= $service->langData['title_label_places'] ?></th>
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