{% extends "dialog.tpl" %}
{% set number = component.number %}
{% set meter = component.meter %}
{% block title %}Диалог редактирования времени производства счетчика{% endblock title %}
{% block dialog %}
	<input type="text" class="dialog-input-date_release" value="{{ meter.get_date_release()|date('d.m.Y') }}">	
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_date_release">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет время производства счетчика привязанного к лицевому счету
	$('.update_date_release').click(function(){
		$.get('update_date_release',{
			number_id: {{ number.get_id() }},
			meter_id: {{ meter.get_id() }},
			serial: '{{ meter.get_serial() }}',
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