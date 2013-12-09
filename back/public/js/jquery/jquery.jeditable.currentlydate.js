/*
 * Currentlydate read only for Jeditable
 *
 *alexandra.benavides
 *
 */

// add :focus selector
jQuery.expr[':'].focus = function(elem) {
	return elem === document.activeElement && (elem.type || elem.href);
};

$.editable
		.addInputType(
				'get_dateTime',
				{

					/* create input element */
					element : function(settings, original) {
						var form = $(this), input = $('<input id="currently_date_" READONLY="readonly"/>');
						form.append(input);
						return input;
					},
					content : function(string, settings, original) {
						$("#currently_date_", this).each(function() {
							$stringdate = get_dateTime();
							$(this).val($stringdate);
						});

					}
				});
$.editable.addInputType(
		'get_date',
		{

			/* create input element */
			element : function(settings, original) {
				var form = $(this), input = $('<input id="currently_date_" READONLY="readonly"/>');
				form.append(input);
				return input;
			},
			content : function(string, settings, original) {

				$("#currently_date_", this).each(function() {
					$stringdate = get_date();
					$(this).val($stringdate);
				});

			}
		});
$.editable.addInputType(
		'get_time',
		{

			/* create input element */
			element : function(settings, original) {
				var form = $(this), input = $('<input id="currently_date_" READONLY="readonly"/>');
				form.append(input);
				return input;
			},
			content : function(string, settings, original) {

				$("#currently_date_", this).each(function() {
					$stringdate = get_time();
					$(this).val($stringdate);
				});

			}
		});
function get_dateTime() {
	var today = new Date();
	var month = today.getMonth() + 1;
	var year = today.getYear();
	var day = today.getDate();
	if (day < 10)
		day = "0" + day;
	if (month < 10)
		month = "0" + month;
	if (year < 1000)
		year += 1900;

	timeString = day + "/" + month + "/" + year + " ";
	intHours = today.getHours();
	intMinutes = today.getMinutes();
	intSeconds = today.getSeconds();
	if (intHours < 10) {
		hours = "0" + intHours + ":";
	} else {
		hours = intHours + ":";
	}
	if (intMinutes < 10) {
		minutes = "0" + intMinutes + " ";
	} else {
		minutes = intMinutes + " ";
	}
	timeString = timeString + hours + minutes;
	return timeString;
}
function get_date(id, editable) {
	var today = new Date();
	var month = today.getMonth() + 1;
	var year = today.getYear();
	var day = today.getDate();
	if (day < 10)
		day = "0" + day;
	if (month < 10)
		month = "0" + month;
	if (year < 1000)
		year += 1900;
	timeString=day + "/" + month + "/" + year;
	return timeString;
}
function get_time(id, editable) {
	var today = new Date();
	intHours = today.getHours();
	intMinutes = today.getMinutes();
	intSeconds = today.getSeconds();
	if (intHours < 10) {
		hours = "0" + intHours + ":";
	} else {
		hours = intHours + ":";
	}
	if (intMinutes < 10) {
		minutes = "0" + intMinutes + " ";
	} else {
		minutes = intMinutes + " ";
	}
	timeString = hours + minutes;
	return timeString;
}
