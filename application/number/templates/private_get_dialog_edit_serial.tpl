{% extends "dialog.tpl" %}
{% set meter = component.meters[0] %}
{% block title %}Диалог редактирования серийного номера счетчика{% endblock title %}
{% block dialog %}
	<input type="text" class="dialog-input-serial form-control" value="{{ meter.serial}}">
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary update_serial">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет серийный номер счетчика привязанного к лицевому счету
	$('.update_serial').click(function(){
		$.get('update_serial',{
			number_id: {{ meter.number_id }},
			meter_id: {{ meter.meter_id }},
			serial: '{{ meter.serial }}',
			new_serial: $('.dialog-input-serial').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}