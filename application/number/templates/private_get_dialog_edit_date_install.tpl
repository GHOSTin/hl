{% extends "dialog.tpl" %}
{% set number = component.number %}
{% set meter = component.meter %}
{% block title %}Диалог редактирования времени установка счетчика{% endblock title %}
{% block dialog %}
	<input type="text" class="dialog-input-date_install" value="{{ meter.get_date_install()|date('d.m.Y') }}">	
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_date_install">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет время установки счетчика привязанного к лицевому счету
	$('.update_date_install').click(function(){
		$.get('update_date_install',{
			number_id: {{ number.get_id() }},
			meter_id: {{ meter.get_id() }},
			serial: '{{ meter.get_serial() }}',
			date: $('.dialog-input-date_install').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});

	// датапикер
	$('.dialog-input-date_install').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
		$('.dialog-input-date_install').datepicker('hide');
	});
{% endblock script %}