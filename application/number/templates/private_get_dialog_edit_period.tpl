{% extends "dialog.tpl" %}
{% set number = component.number %}
{% set meter = component.meter %}
{% set year = meter.get_period() // 12 %}
{% set month = meter.get_period() % 12 %}
{% block title %}Диалог редактирования периода счетчика{% endblock title %}
{% block dialog %}
	<select class="dialog-input-period" style="width:100px">
	{% for period in meter.get_periods() %}
		<option value="{{ period }}">{{ period //12 }} г {{ period %12 }} мес</option>
	{% endfor %}
	</select>
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_period">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет период поверки счетчика привязанного к лицевому счету
	$('.update_period').click(function(){
		$.get('update_period',{
			number_id: {{ number.get_id() }},
			meter_id: {{ meter.get_id() }},
			serial: '{{ meter.get_serial() }}',
			period: $('.dialog-input-period').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}