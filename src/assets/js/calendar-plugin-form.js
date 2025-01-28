window.addEventListener('DOMContentLoaded', () => {
    calendar_form_setup();
});

function calendar_form_setup() {
    let button = document.querySelector('#calendarFormModalAddActivityButton');
    if(button) {
        let get_rest_url = document.querySelector("#get_rest_url");
        let get_rest_url_value = null;
        if(get_rest_url) {
            get_rest_url_value = get_rest_url.value;
        } else {
			get_rest_url = document.querySelector("#get_rest_url2");
			if(get_rest_url) {
				get_rest_url_value = get_rest_url.value;
			}
		}

        let modal_form = document.querySelector('#calendarFormModalAddActivity');
        if(modal_form) {
            let modal_form_object = new bootstrap.Modal(modal_form, {});

            button.addEventListener('click', () => {
                modal_form_object.show();
            });
    
            modal_form.addEventListener('click', (e) => {
                if(e.target.id == "calendarFormModalAddActivity" || e.target.hasAttribute('data-dismiss')) {
                    modal_form_object.hide();
                }
            });

            let url = get_rest_url_value + '/calendar-grid-form/add-grid-activity'
            let submit_calendar_modal_form_add_activity = document.querySelector('#submit_calendar_modal_form_add_activity');
            let recaptcha_response_contact = document.querySelector('#recaptcha_response_contact');

            submit_calendar_modal_form_add_activity.addEventListener('click', (e) => {
                e.preventDefault();
                let site_key = e.target.getAttribute('data-target');

                if(site_key !== null && site_key !== "") {
                    grecaptcha.ready(function() {
                        grecaptcha.execute("" + site_key, {
                            action: 'calendar_modal_form_add_activity'
                        })
                        .then(function(token) {
                            recaptcha_response_contact.value = token;
    
                            calendar_form_submit(url, 'calendar_modal_form_add_activity');
    
                            modal_form_object.hide();
                            window.scrollTo(0,0);
                        });
                    });
                } else {
                    calendar_form_submit(url, 'calendar_modal_form_add_activity');
    
                    modal_form_object.hide();
                    window.scrollTo(0,0);
                }                
            });
        }
    }
}