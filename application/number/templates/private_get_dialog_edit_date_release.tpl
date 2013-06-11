{% extends "dialog.tpl" %}
{% set meter = component.meters[0] %}
{% block title %}Диалог редактирования времени установка счетчика{% endblock title %}
{% block dialog %}
	<input type="text" class="dialog-input-date_release" value="{{ meter.date_release|date('d.m.Y') }}">	
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_date_release">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет время установки счетчика привязанного к лицевому счету
	$('.update_date_release').click(function(){
		$.get('update_date_release',{
			number_id: {{ meter.number_id }},
			meter_id: {{ meter.meter_id }},
			serial: '{{ meter.serial }}',
			date: $('.dialog-input-date_release').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});

	// датапикер
	$('.dialog-input-date_release').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
		$('.dialog-input-date_release').datepicker('hide');
	});
{% endblock script %}