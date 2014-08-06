{% extends "dialog.tpl" %}
{% set number = response.number %}
{% set meter = response.meter %}
{% block title %}Диалог редактирования комментария счетчика{% endblock title %}
{% block dialog %}
	<textarea class="dialog-input-comment" rows="5">{{ meter.get_comment() }}</textarea>
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary update_meter_comment">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет комментарий счетчика привязанного к лицевому счету
	$('.update_meter_comment').click(function(){
		$.get('update_meter_comment',{
			number_id: {{ number.get_id() }},
			meter_id: {{ meter.get_id() }},
			serial: '{{ meter.get_serial() }}',
			comment: $('.dialog-input-comment').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}