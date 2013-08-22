{% extends "dialog.tpl" %}
{% set meter = component.meter %}
{% block title %}Диалог удаления привязки счетчика{% endblock title %}
{% block dialog %}
Вы действительно хотите удалить привязку счетчика к лицевому счете, а также показания?
{% endblock dialog %}
{% block buttons %}
	<div class="btn delete_meter">Удалить</div>
{% endblock buttons %}
{% block script %}
	// Удаляет счетчик из лицевого счета
	$('.delete_meter').click(function(){
		$.get('delete_meter',{
			number_id: {{ meter.get_number_id() }},
			meter_id: {{ meter.get_meter_id() }},
			serial: '{{ meter.get_serial() }}'
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}