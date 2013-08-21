{% extends "dialog.tpl" %}
{% set meter = component.meter %}
{% block title %}Диалог редактирования серийного номера счетчика{% endblock title %}
{% block dialog %}
	<input type="text" class="dialog-input-serial" value="{{ meter.get_serial() }}">	
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_serial">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет серийный номер счетчика привязанного к лицевому счету
	$('.update_serial').click(function(){
		$.get('update_serial',{
			number_id: {{ meter.get_number_id() }},
			meter_id: {{ meter.get_meter_id() }},
			serial: '{{ meter.get_serial() }}',
			new_serial: $('.dialog-input-serial').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}