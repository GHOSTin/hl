{% extends "dialog.tpl" %}
{% set meter = component.meter %}
{% set places = {'kitchen':'Кухня', 'toilet':'Туалет', 'bathroom':'Ванна'} %}
{% block title %}Диалог редактирования места установки счетчика{% endblock title %}
{% block dialog %}
	<select class="dialog-select-place">
	{% for key, place in places %}
		{% if meter.place == key %}
			{% set selected = ' selected' %}
		{% else %}
			{% set selected = '' %}
		{% endif %}
		<option value="{{ key }}"{{ selected }}>{{ place }}</option>
	{% endfor %}
	</select>
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_meter_place">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет период поверки счетчика привязанного к лицевому счету
	$('.update_meter_place').click(function(){
		$.get('update_meter_place',{
			number_id: {{ meter.number_id }},
			meter_id: {{ meter.meter_id }},
			serial: '{{ meter.serial }}',
			place: $('.dialog-select-place').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}