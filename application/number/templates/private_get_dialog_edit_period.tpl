{% extends "dialog.tpl" %}
{% set meter = component.meter %}
{% set year = meter.get_period() // 12 %}
{% set month = meter.get_period() % 12 %}
{% block title %}Диалог редактирования периода счетчика{% endblock title %}
{% block dialog %}
	<select class="dialog-input-year col-3">
		{% for i in 0..19 %}
			<option{% if i == year %} selected{% endif %}>{{ i }}</option>
		{% endfor %}
	</select> г.
	<select class="dialog-input-month col-3">
		{% for i in 0..11 %}
			<option{% if i == month %} selected{% endif %}>{{ i }}</option>
		{% endfor %}
	</select> мес.
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary update_period">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет период поверки счетчика привязанного к лицевому счету
	$('.update_period').click(function(){
		$.get('update_period',{
			number_id: {{ meter.get_number_id() }},
			meter_id: {{ meter.get_meter_id() }},
			serial: '{{ meter.get_serial() }}',
			year: $('.dialog-input-year').val(),
			month: $('.dialog-input-month').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}