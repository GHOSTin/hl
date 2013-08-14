{% extends "dialog.tpl" %}
{% set meter = component.meter %}
{% block title %}Диалог редактирования комментария счетчика{% endblock title %}
{% block dialog %}
	<textarea class="dialog-input-comment" style="width:90%">{{ meter.comment }}</textarea>
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_meter_comment">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет комментарий счетчика привязанного к лицевому счету
	$('.update_meter_comment').click(function(){
		$.get('update_meter_comment',{
			number_id: {{ meter.number_id }},
			meter_id: {{ meter.meter_id }},
			serial: '{{ meter.serial }}',
			comment: $('.dialog-input-comment').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}