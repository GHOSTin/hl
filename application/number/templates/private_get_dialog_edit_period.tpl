{% extends "dialog.tpl" %}
{% set meter = component.meters[0] %}
{% set year = meter.period // 12 %}
{% set month = meter.period % 12 %}
{% block title %}Диалог редактирования периода счетчика{% endblock title %}
{% block dialog %}
	<select class="dialog-input-year" style="width:100px">
		{% for i in 0..19 %}
			<option{% if i == year %} selected{% endif %}>{{ i }}</option>
		{% endfor %}
	</select> г.
	<select class="dialog-input-month" style="width:100px">
		{% for i in 0..11 %}
			<option{% if i == month %} selected{% endif %}>{{ i }}</option>
		{% endfor %}
	</select> мес.
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_period">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет период поверки счетчика привязанного к лицевому счету
	$('.update_period').click(function(){
		$.get('update_period',{
			number_id: {{ meter.number_id }},
			meter_id: {{ meter.meter_id }},
			serial: '{{ meter.serial }}',
			year: $('.dialog-input-year').val(),
			month: $('.dialog-input-month').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}