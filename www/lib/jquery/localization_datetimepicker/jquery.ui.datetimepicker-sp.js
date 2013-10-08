jQuery(function($){

	$.timepicker.regional['sp'] = { 
			currentText: 'Ahora',
			closeText: 'Cerrar',
			ampm: false,
			amNames: ['AM', 'A'],
			pmNames: ['PM', 'P'],
			timeFormat: 'hh:mm tt',
			timeSuffix: '',
			timeOnlyTitle: 'Seleccione el Tiempo',
			timeText: 'Tiempo',
			hourText: 'Hora',
			minuteText: 'Minuto',
			secondText: 'Segundo',
			millisecText: 'Millisegundo',
			timezoneText: 'Zona Horaria'
		};
	$.timepicker.setDefaults($.timepicker.regional['sp']);
});