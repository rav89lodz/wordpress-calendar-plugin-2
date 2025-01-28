window.addEventListener('DOMContentLoaded', () => {
    calendar_admin_menu_setup();
    save_main_menu_data();

    run_tooltip();
    copy_short_code_to_clipboard();

    place_edit_action();
    place_delete_action();

    grid_edit_action();
    grid_delete_action();

    set_element_visible_in_calendar();

    setup_delete_for_grid_reservation();
    setup_edit_for_grid_reservation();

    setup_delete_for_user_reservation();
    setup_update_for_user_reservation();
});

function run_tooltip() {
    const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipElements.forEach(element => {
        new bootstrap.Tooltip(element);
    });
}

function copy_short_code_to_clipboard() {
    let items = document.querySelectorAll('.my-copy-short-code');
    if(items !== null && items.length > 0) {
        items.forEach((item) => {
            item.addEventListener('click', (e) => {
                if(e.target.firstChild) {
                    navigator.clipboard.writeText(e.target.firstChild.nodeValue);
                    show_copy_tooltip(null);
                }
            });
        });
    }
}

function clean_up_activity_place_form_group() {
    let activity_place_form_group = document.querySelector('#activity_place_form_group');
    if(activity_place_form_group) {
        while (activity_place_form_group.firstChild) {
            activity_place_form_group.removeChild(activity_place_form_group.lastChild);
        }
    }

    let activity_place_form_group_2 = document.querySelector('#activity_place_form_group_2');
    if(activity_place_form_group) {
        while (activity_place_form_group_2.firstChild) {
            activity_place_form_group_2.removeChild(activity_place_form_group_2.lastChild);
        }
    }
}

function clean_up_new_grid_activity_form_group() {
    let new_grid_activity_form_group = document.querySelector('#new_grid_activity_form_group');
    if(new_grid_activity_form_group) {
        while (new_grid_activity_form_group.firstChild) {
            new_grid_activity_form_group.removeChild(new_grid_activity_form_group.lastChild);
        }
    }

    let new_grid_activity_form_group_2 = document.querySelector('#new_grid_activity_form_group_2');
    if(new_grid_activity_form_group_2) {
        while (new_grid_activity_form_group_2.firstChild) {
            new_grid_activity_form_group_2.removeChild(new_grid_activity_form_group_2.lastChild);
        }
    }
}

function clean_up_activity_grid_form_group() {
    let activity_grid_form_group = document.querySelector('#activity_grid_form_group');
    if(activity_grid_form_group) {
        while (activity_grid_form_group.firstChild) {
            activity_grid_form_group.removeChild(activity_grid_form_group.lastChild);
        }
    }

    let activity_grid_form_group_2 = document.querySelector('#activity_grid_form_group_2');
    if(activity_grid_form_group_2) {
        while (activity_grid_form_group_2.firstChild) {
            activity_grid_form_group_2.removeChild(activity_grid_form_group_2.lastChild);
        }
    }
}

function place_edit_action() {
    let items = document.querySelectorAll('.btn-place-update-action');
    let activity_place_form_group = document.querySelector('#activity_place_form_group');
    let activity_place_description = document.querySelector('#activity_place_description');

    if(items !== null && items.length > 0) {
        items.forEach((item) => {
            item.addEventListener('click', (e) => {
                clean_up_activity_place_form_group();

                let id = e.target.parentNode.parentNode.firstChild.innerText;
                let name = e.target.parentNode.parentNode.childNodes[1].innerText;

                const hiddenInputUpdate = document.createElement('input');
                hiddenInputUpdate.setAttribute("name", "activity_place_id_update");
                hiddenInputUpdate.type = "hidden";
                hiddenInputUpdate.value = id;

                activity_place_description.value = name;
                activity_place_form_group.appendChild(hiddenInputUpdate);

                show_modal_calendar_plugin_form("calendarFormModalAddPlace");
            });
        });
    }
}

function place_delete_action() {
    let items = document.querySelectorAll('.btn-place-delete-action');
    let activity_place_form_group = document.querySelector('#activity_place_form_group_2');
    let plugin_place_item_to_remove = document.querySelector('#plugin_place_item_to_remove');

    if(items !== null && items.length > 0) {
        items.forEach((item) => {
            item.addEventListener('click', (e) => {
                clean_up_activity_place_form_group();

                let id = e.target.parentNode.parentNode.firstChild.innerText;
                let name = e.target.parentNode.parentNode.childNodes[1].innerText;
                plugin_place_item_to_remove.innerText = name;

                const hiddenInputDelete = document.createElement('input');
                hiddenInputDelete.setAttribute("name", "activity_place_id_delete");
                hiddenInputDelete.type = "hidden";
                hiddenInputDelete.value = id;

                activity_place_form_group.appendChild(hiddenInputDelete);
                show_modal_calendar_plugin_form("calendarFormModalRemovePlace");
            });
        });
    }
}

function set_element_visible_in_calendar() {
    let items = document.querySelectorAll('.calendar-activity-is-active');
    if(items) {
        items.forEach((item) => {
            item.addEventListener('change', (e) => {
                let data = e.target.getAttribute('data-target');
                let object = {};
                object["id"] = data;
                object["activity_is_active"] = e.target.checked;
                save_grid_menu_data(object);
            });
        });
    }
}

function fill_or_clean_grid_data(data, places, fill_flag) {
    let activity_name = document.querySelector('#activity_name');
    let activity_start_at = document.querySelector('#activity_start_at');
    let activity_end_at = document.querySelector('#activity_end_at');
    let activity_cyclic = document.querySelector('#activity_cyclic');
    let activity_date = document.querySelector('#activity_date');
    let activity_day = document.querySelector('#activity_day');
    let activity_bg_color = document.querySelector('#activity_bg_color');
    let activity_type = document.querySelector('#activity_type');
    let activity_slot = document.querySelector('#activity_slot');
    let activity_is_active = document.querySelector('#activity_is_active');

    let activity_date_div = document.querySelector('#activity_date_div');
    let activity_day_div = document.querySelector('#activity_day_div');
    let activity_cyclic_hidden = document.querySelector('#activity_cyclic_hidden');
    
    if(fill_flag === false) {
        activity_name.value = null;
        activity_start_at.value = "12:00";
        activity_end_at.value = "13:00";
        activity_bg_color.value = "#ff0000";
        activity_slot.value = 1;
        activity_is_active.value = "1";
        
        if(places.length > 0 && places[0].includes(">?>")) {
            let place = places[0];
            place = place.split('>?>');
            activity_type.value = place[0];
        } else {
            activity_type.value = null;
        }

        activity_cyclic.checked = false;
        activity_cyclic_hidden.value = 0;
        activity_date_div.classList.remove('d-none');
        activity_date.value = null;
        activity_date.setAttribute('required', true);
        activity_day_div.classList.add('d-none');
        activity_day.removeAttribute('required');
        activity_day.value = "monday";        
    } else {
        activity_name.value = data[1];
        activity_start_at.value = data[2];
        activity_end_at.value = data[3];
        activity_bg_color.value = data[7];
        activity_slot.value = data[9];
        activity_is_active.value = data[10];

        activity_cyclic.checked = (data[4] == 1 ? true : false);

        if(activity_cyclic.checked == true) {
            activity_date_div.classList.add('d-none');
            activity_date.removeAttribute('required');
            activity_day_div.classList.remove('d-none');
            activity_day.setAttribute('required', true);
            activity_cyclic_hidden.value = 1;
        } else {
            activity_date_div.classList.remove('d-none');
            activity_date.setAttribute('required', true);
            activity_day_div.classList.add('d-none');
            activity_day.removeAttribute('required');
            activity_cyclic_hidden.value = 0;
        }
        
        activity_date.value = (data[5] == "" ? null : data[5]);
        let week_days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
        for (let item of activity_day.options) {
            item.selected = false;
            week_days.forEach((wd) => {
                if(data[6].includes(wd) && item.value == wd) {
                    item.selected = true;
                    return;
                }
            });
        }
        
        if(places.length > 0 && places[0].includes(">?>")) {
            places.forEach((p) => {
                let place = p.split('>?>');
                if(place[1] == data[8]) {
                    activity_type.value = place[0];
                }
            });
        } else {
            activity_type.value = null;
        }
    }
	plugin_grid_data_events();
}

function grid_edit_action() {
    let items = document.querySelectorAll('.btn-grid-update-action');
    let activity_grid_form_group = document.querySelector('#activity_grid_form_group');

    if(items !== null && items.length > 0) {
        items.forEach((item) => {
            item.addEventListener('click', (e) => {
                clean_up_activity_grid_form_group();

                let data = e.target.getAttribute('data-target');
                data = data.split('|,|');
                let places = e.target.getAttribute('data-places');
                places = places.split('|,|');

                if(data.length === 11) {
                    fill_or_clean_grid_data(null, places, false);
                    fill_or_clean_grid_data(data, places, true);

                    const hiddenInputUpdate = document.createElement('input');
                    hiddenInputUpdate.setAttribute("name", "activity_grid_id_update");
                    hiddenInputUpdate.type = "hidden";
                    hiddenInputUpdate.value = data[0];
    
                    activity_grid_form_group.appendChild(hiddenInputUpdate);
    
                    show_modal_calendar_plugin_form("calendarFormModalAddGrid");
                } else {
                    console.error('incorrect data-target');
                }
            });
        });
    }
}

function grid_delete_action() {
    let items = document.querySelectorAll('.btn-grid-delete-action');
    let activity_grid_form_group = document.querySelector('#activity_grid_form_group_2');
    let plugin_grid_item_to_remove = document.querySelector('#plugin_grid_item_to_remove');

    if(items !== null && items.length > 0) {
        items.forEach((item) => {
            item.addEventListener('click', (e) => {
                clean_up_activity_grid_form_group();

                let data = e.target.getAttribute('data-target');
                data = data.split('|,|');

                if(data.length === 11) {
                    const hiddenInputDelete = document.createElement('input');
                    hiddenInputDelete.setAttribute("name", "activity_grid_id_delete");
                    hiddenInputDelete.type = "hidden";
                    hiddenInputDelete.value = data[0];

                    plugin_grid_item_to_remove.innerText = data[1];

                    activity_grid_form_group.appendChild(hiddenInputDelete);
                    show_modal_calendar_plugin_form("calendarFormModalRemoveGridItem");
                } else {
                    console.error('incorrect data-target');
                }
            });
        });
    }
}

function show_copy_tooltip(text) {
    let notification = document.getElementById('copyNotification');

    if(text !== null) {
        notification.innerText = text;
    }

    notification.style.display = 'block';
    setTimeout(() => {
        notification.style.opacity = 1;
    }, 10);

    setTimeout(() => {
        notification.style.opacity = 0;
        setTimeout(() => {
            notification.style.display = 'none';
        }, 300);
        if(text !== null) {
            window.location.reload();
        }
    }, 2000);
}

function calendar_admin_menu_setup() {
    let calendar_plugin_one_day_view = document.querySelector('#calendar_plugin_one_day_view');
    if(calendar_plugin_one_day_view) {
        let calendar_plugin_horizontal_calendar_grid_div = document.querySelector('#calendar_plugin_horizontal_calendar_grid_div');
        let calendar_plugin_horizontal_calendar_grid_hr = document.querySelector('#calendar_plugin_horizontal_calendar_grid_hr');
        let calendar_plugin_horizontal_calendar_grid = document.querySelector('#calendar_plugin_horizontal_calendar_grid');

        if(calendar_plugin_one_day_view.checked == true) {
            calendar_plugin_horizontal_calendar_grid_div.classList.add('d-none');
            calendar_plugin_horizontal_calendar_grid_hr.classList.add('d-none');
            calendar_plugin_horizontal_calendar_grid.checked = false;
        }
        
        calendar_plugin_one_day_view.addEventListener('change', () => {
            if(calendar_plugin_one_day_view.checked == true) {
                calendar_plugin_horizontal_calendar_grid_div.classList.add('d-none');
                calendar_plugin_horizontal_calendar_grid_hr.classList.add('d-none');
                calendar_plugin_horizontal_calendar_grid.checked = false;
            } else {
                calendar_plugin_horizontal_calendar_grid_div.classList.remove('d-none');
                calendar_plugin_horizontal_calendar_grid_hr.classList.remove('d-none');
            }
        });
    }

    let button_place = document.querySelector('#calendarFormModalAddPlaceButton');
    if(button_place) {
        button_place.addEventListener('click', () => {
            clean_up_activity_place_form_group();
            let activity_place_description = document.querySelector('#activity_place_description');
            activity_place_description.value = null;
            show_modal_calendar_plugin_form("calendarFormModalAddPlace");
        });
    }

    let button_grid = document.querySelector('#calendarFormModalAddGridButton');
    if(button_grid) {
        button_grid.addEventListener('click', (e) => {
            e.preventDefault();
            let places = e.target.getAttribute('data-places');
            places = places.split('|,|');

            fill_or_clean_grid_data(null, places, false);
            clean_up_activity_grid_form_group();
            show_modal_calendar_plugin_form("calendarFormModalAddGrid");
        });
    }
}

function show_modal_calendar_plugin_form(modal_name) {
    let modal_form = document.querySelector('#' + modal_name);
    if(modal_form) {
        let modal_form_object = new bootstrap.Modal(modal_form, {});

        modal_form_object.show();
        modal_form.addEventListener('click', (e) => {
            if(e.target.id == modal_name || e.target.hasAttribute('data-dismiss')) {
                modal_form_object.hide();
            }
        });
    }
}

function handle_plugin_rest_response2(xmlhttp) {
    let notification = document.getElementById('copyNotification');

    try {
        let jsonResponse = JSON.parse(xmlhttp.responseText);
        let parser = new DOMParser();
        let response = parser.parseFromString(jsonResponse, "text/html");

        notification.classList.remove('notification-clipboard-error');

        if (xmlhttp.status === 200) {
            show_copy_tooltip(response.firstChild.innerText);
        } else {
            notification.classList.add('notification-clipboard-error');
            show_copy_tooltip(response.firstChild.innerText);
        }
    } catch(error) {
        console.error(error);
    }
}

function handle_plugin_rest_response(xmlhttp) {
    try {
        console.log(xmlhttp.responseText);
        let jsonResponse = JSON.parse(xmlhttp.responseText);
        let parser = new DOMParser();
        let response = parser.parseFromString(jsonResponse, "text/html");

        if (xmlhttp.status === 200) {
            document.querySelector('.my-alert-success').style.display = "block";
            document.querySelector("#form_success").innerText = response.firstChild.innerText;
        } else {
            document.querySelector('.my-alert-error').style.display = "block";
            document.querySelector("#form_error").innerText = response.firstChild.innerText;
        }
    } catch(error) {
        console.error(error);
    } finally {
        setTimeout(() => {
            document.querySelector('.my-alert-success').style.display = "none";
            document.querySelector('.my-alert-error').style.display = "none";
        }, 3000);
    }
}

function save_grid_menu_data(object) {
    let get_rest_url = document.querySelector("#get_rest_url");
    let get_rest_url_value = '';
    if(get_rest_url) {
        get_rest_url_value = get_rest_url.value;
    }

    let url = get_rest_url_value + '/calendar-admin-menu/grid-form';

    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
        if (xmlhttp.readyState === 4) {
            handle_plugin_rest_response2(xmlhttp);
        }
    }

    xmlhttp.open("POST", url);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(JSON.stringify(object));
}

function save_main_menu_data() {
    let submit_calendar_form_main_menu = document.querySelector('#submit_calendar_form_main_menu');
    if(submit_calendar_form_main_menu) {
        let get_rest_url = document.querySelector("#get_rest_url");
        let get_rest_url_value = '';
        if(get_rest_url) {
            get_rest_url_value = get_rest_url.value;
        }

        let url = get_rest_url_value + '/calendar-admin-menu/main-form'

        submit_calendar_form_main_menu.addEventListener('click', (e) => {
            e.preventDefault();
            calendar_form_admin_menu_submit(url, 'calendar_form_main_menu');
            window.scrollTo(0,0);
        });
    }
}

function create_admin_menu_calendar_data(object_name) {
    let calendar_modal_form = document.querySelector('#' + object_name);
    const form_data = new FormData(calendar_modal_form);
    let object = {};
    form_data.forEach((value, key) => {
        if(!Reflect.has(object, key)) {
            object[key] = value;
            return;
        }
        if(!Array.isArray(object[key])) {
            object[key] = [object[key]];
        }
        object[key].push(value);
    });
    return object;
}

function calendar_form_admin_menu_submit(url, object_name) {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
        if (xmlhttp.readyState === 4) {
            handle_plugin_rest_response(xmlhttp);
        }
    }

    xmlhttp.open("POST", url);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(JSON.stringify(create_admin_menu_calendar_data(object_name)));
}

function plugin_grid_data_events() {
    let activity_cyclic = document.querySelector('#activity_cyclic');     
    if(activity_cyclic) {
        let activity_day = document.querySelector('#activity_day');
        let activity_date = document.querySelector('#activity_date');
        let activity_date_div = document.querySelector('#activity_date_div');
        let activity_day_div = document.querySelector('#activity_day_div');
        let activity_cyclic_hidden = document.querySelector('#activity_cyclic_hidden');
        activity_cyclic.addEventListener('change', () => {
            if(activity_cyclic.checked === true) {
                activity_day_div.classList.remove('d-none');
                activity_day.setAttribute('required', true);
                activity_date_div.classList.add('d-none');
                activity_date.removeAttribute('required');
                activity_date.value = null;
                activity_cyclic_hidden.value = 1;
            } else {
                activity_date.setAttribute('required', true);
                activity_date_div.classList.remove('d-none');
                activity_day.removeAttribute('required');
                activity_day_div.classList.add('d-none');
                activity_day.value = 'monday';
                activity_cyclic_hidden.value = 0;
            }
        });
    }
}

function setup_delete_for_grid_reservation() {
    let items = document.querySelectorAll('.btn-add-new-grid-delete-action');
    let new_grid_activity_item_to_remove = document.querySelector('#new_grid_activity_item_to_remove');
    let new_grid_activity_form_group = document.querySelector('#new_grid_activity_form_group_2');

    if(items !== null && items.length > 0) {
        items.forEach((item) => {
            item.addEventListener('click', (e) => {
                clean_up_new_grid_activity_form_group();
                let data = e.target.getAttribute('data-target');
                data = data.split('|>|');

                if(data.length === 8) {
                    new_grid_activity_item_to_remove.innerHTML = data[1] + "<br>" + data[2];

                    const hiddenInputDelete = document.createElement('input');
                    hiddenInputDelete.setAttribute("name", "new_grid_activity_id_delete");
                    hiddenInputDelete.type = "hidden";
                    hiddenInputDelete.value = data[0];

                    new_grid_activity_form_group.appendChild(hiddenInputDelete);

                    show_modal_calendar_plugin_form("calendarFormModalRemoveGridActivity");
                }
            });
        });
    }
}

function setup_edit_for_grid_reservation() {
    let items = document.querySelectorAll('.btn-add-new-grid-update-action');
    let new_grid_activity_form_group = document.querySelector('#new_grid_activity_form_group');

    if(items !== null && items.length > 0) {
        items.forEach((item) => {
            item.addEventListener('click', (e) => {
                clean_up_new_grid_activity_form_group();
                let data = e.target.getAttribute('data-target');
                data = data.split('|>|');

                if(data.length === 8) {

                    const hiddenInputUpdate = document.createElement('input');
                    hiddenInputUpdate.setAttribute("name", "new_grid_activity_id_update");
                    hiddenInputUpdate.type = "hidden";
                    hiddenInputUpdate.value = data[0];

                    new_grid_activity_form_group.appendChild(hiddenInputUpdate);

                    set_edit_form_for_grid_reservation(data);

                    show_modal_calendar_plugin_form("calendarFormModalAddGridActivity");
                }
            });
        });
    }
}

function set_edit_form_for_grid_reservation(data) {
    let activity_name = document.querySelector('#activity_name');
    let activity_start_at = document.querySelector('#activity_start_at');
    let activity_end_at = document.querySelector('#activity_end_at');
    let activity_cyclic = document.querySelector('#activity_cyclic');
    let activity_cyclic_hidden = document.querySelector('#activity_cyclic_hidden');
    let activity_date_div = document.querySelector('#activity_date_div');
    let activity_date = document.querySelector('#activity_date');
    let activity_day_div = document.querySelector('#activity_day_div');
    let activity_day = document.querySelector('#activity_day');
    let calendar_status = document.querySelector('#calendar_status');

    if(activity_name) {
        activity_name.value = data[1];
        activity_start_at.value = data[3];
        activity_end_at.value = data[4];
        activity_cyclic_hidden.value = data[6];
        calendar_status.value = data[7];
        if(data[6] == "1") {
            activity_cyclic.checked = true;
            activity_day_div.classList.remove('d-none');
            activity_day.setAttribute('required', true);
            activity_date_div.classList.add('d-none');
            activity_date.removeAttribute('required');
            activity_date.value = null;
            activity_day.value = "monday";
        } else {
            activity_cyclic.checked = false;
            activity_day_div.classList.add('d-none');
            activity_day.removeAttribute('required');
            activity_date_div.classList.remove('d-none');
            activity_date.setAttribute('required', true);
            activity_date.value = data[5];
            activity_day.value = "monday";
        }

        plugin_grid_data_events();
    }

}

function setup_delete_for_user_reservation() {
    let items = document.querySelectorAll('.btn-user-reservation-delete-action');
    let user_reservation_item_to_remove = document.querySelector('#user_reservation_item_to_remove');
    let users_reservation_form_group = document.querySelector('#users_reservation_form_group_2');

    if(items !== null && items.length > 0) {
        items.forEach((item) => {
            item.addEventListener('click', (e) => {
                let data = e.target.getAttribute('data-target');
                data = data.split('|>|');

                if(data.length === 4) {
                    user_reservation_item_to_remove.innerHTML = data[1] + "<br>" + data[2];

                    const hiddenInputDelete = document.createElement('input');
                    hiddenInputDelete.setAttribute("name", "users_reservation_activity_id_delete");
                    hiddenInputDelete.type = "hidden";
                    hiddenInputDelete.value = data[0];

                    users_reservation_form_group.appendChild(hiddenInputDelete);

                    show_modal_calendar_plugin_form("calendarFormModalUsersReservation");
                }
            });
        });
    }
}

function setup_update_for_user_reservation() {
    let items = document.querySelectorAll('.btn-user-reservation-update-action');
    let user_reservation_item_to_update = document.querySelector('#user_reservation_item_to_update');
    let users_reservation_form_group = document.querySelector('#users_reservation_form_group');
    let calendar_status = document.querySelector('#calendar_status');

    if(items !== null && items.length > 0) {
        items.forEach((item) => {
            item.addEventListener('click', (e) => {
                let data = e.target.getAttribute('data-target');
                data = data.split('|>|');

                if(data.length === 4) {
                    user_reservation_item_to_update.innerHTML = data[1] + "<br>" + data[2];

                    const hiddenInputDelete = document.createElement('input');
                    hiddenInputDelete.setAttribute("name", "users_reservation_activity_id_update");
                    hiddenInputDelete.type = "hidden";
                    hiddenInputDelete.value = data[0];

                    users_reservation_form_group.appendChild(hiddenInputDelete);

                    calendar_status.value = data[3];

                    show_modal_calendar_plugin_form("calendarFormModalUpdateUsersReservation");
                }
            });
        });
    }
}