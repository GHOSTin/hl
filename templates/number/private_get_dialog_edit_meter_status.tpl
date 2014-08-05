{% extends "dialog.tpl" %}
{% set number = component.number %}
{% set meter = component.meter %}
{% block title %}Диалог редактирования статуса счетчика{% endblock title %}
{% block dialog %}
	{% if meter.get_status() == 'enabled' %}
		Вы действительно хотите закрыть счетчик?
	{% endif %}
	{% if meter.get_status() == 'disabled' %}
		Вы действительно хотите открыть счетчик?
	{% endif %}
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary update_meter_status">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет период поверки счетчика привязанного к лицевому счету
	$('.update_meter_status').click(function(){
		$.get('update_meter_status',{
			number_id: {{ number.get_id() }},
			meter_id: {{ meter.get_id() }},
			serial: '{{ meter.get_serial() }}',
			{% if meter.get_status() == 'enabled' %}
				status: 'disabled'
			{% endif %}
			{% if meter.get_status() == 'disabled' %}
				status: 'enabled'
			{% endif %}
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}