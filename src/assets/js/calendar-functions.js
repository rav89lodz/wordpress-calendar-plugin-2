Date.prototype.addOrSubtractDays = function(days, operator) {
    let date = new Date(this.valueOf());
    if(operator == '+') {
        date.setDate(date.getDate() + days);
        return date;
    }
    date.setDate(date.getDate() - days);
    return date;
}

Date.prototype.addOrSubtractMonth = function(months, operator) {
    let date = new Date(this.valueOf());

    if(operator == '+') {
        date.setMonth(date.getMonth() + months);
        return date;
    }
    date.setMonth(date.getMonth() - months);
    return date;
}

function send_request_for_dates(param_date, url) {
    param_date = param_date.toISOString();
    send_request_with_data(param_date, url);
}

function send_request_with_data(data, url) {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
        if (xmlhttp.readyState === 4) {
            change_grid_content(xmlhttp);
        }
    }
    let calendar_grid_short_code = document.querySelector('#calendar_grid_short_code');
    let object = {"data": data, "short_code": calendar_grid_short_code.value};

    xmlhttp.open("POST", url);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(JSON.stringify(object));
}

function change_grid_content(xmlhttp) {
    try {
        let jsonResponse = JSON.parse(xmlhttp.responseText);
        let parser = new DOMParser();
        let response = parser.parseFromString(jsonResponse, "text/html");

        if (xmlhttp.status === 200) {
            document.querySelector("#calendar_form_grid1").innerHTML = response.querySelector('#calendar_form_grid1').innerHTML;
            calendar_setup();
            fluent_background_setup();
            modal_setup();
        }
    }
    catch(error) {
        console.error(error);
    }
}

function create_calendar_data(object_name) {
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

function calendar_form_submit(url, object_name) {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
        if (xmlhttp.readyState === 4) {
            try {
                let jsonResponse = JSON.parse(xmlhttp.responseText);
                let parser = new DOMParser();
                let response = parser.parseFromString(jsonResponse, "text/html");
    
                if (xmlhttp.status === 200) {
					let alert_success = document.querySelector("#form_success");
					if(alert_success) {
						document.querySelector('.my-alert-success').style.display = "block";
						alert_success.innerText = response.firstChild.innerText;
					} else {
						alert_success = document.querySelector("#form_success2");
						if(alert_success) {
							document.querySelector('.my-alert-success2').style.display = "block";
							alert_success.innerText = response.firstChild.innerText;
						}
					}
                }
                else {
					let alert_error = document.querySelector("#form_error");
					if(alert_error) {
						document.querySelector('.my-alert-error').style.display = "block";
                    	alert_error.innerText = response.firstChild.innerText;
					} else {
						alert_error = document.querySelector("#form_error2");
						if(alert_error) {
							document.querySelector('.my-alert-error2').style.display = "block";
                    		alert_error.innerText = response.firstChild.innerText;
						}
					}
                    
                }
            }
            catch(error) {
                console.error(error);
            } finally {
				let my_alert_success = document.querySelector('.my-alert-success');
				let my_alert_error = document.querySelector('.my-alert-error');
				if(my_alert_success === null) {
					my_alert_success = document.querySelector('.my-alert-success2');
					my_alert_error = document.querySelector('.my-alert-error2');
				}
				
				if(my_alert_success) {
					setTimeout(() => {
						my_alert_success.style.display = "none";
						my_alert_error.style.display = "none";
					}, 3000);
				}               
            }
        }
    }

    xmlhttp.open("POST", url);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(JSON.stringify(create_calendar_data(object_name)));
}
