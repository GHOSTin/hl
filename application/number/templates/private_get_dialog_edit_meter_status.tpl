{% extends "dialog.tpl" %}
{% set meter = component.meters[0] %}
{% block title %}Диалог редактирования статуса счетчика{% endblock title %}
{% block dialog %}
	{% if meter.status == 'enabled' %}
		Вы действительно хотите закрыть счетчик?
	{% endif %}
	{% if meter.status == 'disabled' %}
		Вы действительно хотите открыть счетчик?
	{% endif %}
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_meter_status">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет период поверки счетчика привязанного к лицевому счету
	$('.update_meter_status').click(function(){
		$.get('update_meter_status',{
			number_id: {{ meter.number_id }},
			meter_id: {{ meter.meter_id }},
			serial: '{{ meter.serial }}',
			{% if meter.status == 'enabled' %}
				status: 'disabled'
			{% endif %}
			{% if meter.status == 'disabled' %}
				status: 'enabled'
			{% endif %}
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}