{% extends "dialog.tpl" %}
{% set number = component.number %}
{% set meter = component.meter %}
{% block title %}Диалог перепривязки счетчика{% endblock title %}
{% block dialog %}
	<select class="dialog-select-service">
		<option value="">Выберите услугу</option>
		<option value="cold_water">Холодное водоснабжение</option>
		<option value="hot_water">Горячее водоснабжение</option>
		<option value="electrical">Электроэнергия</option>
	</select>
	<select class="dialog-select-meters" style="display:block" disabled>
		<option>Ожидание...</option>
	</select>
	<select class="dialog-select-periods" style="display:block" disabled>
		<option>Ожидание...</option>
	</select>
{% endblock dialog %}
{% block buttons %}
	<div class="btn change_meter">Перепривязать</div>
{% endblock buttons %}
{% block script %}
	// изменяет диалог
	$('.dialog-select-meters').change(function(){
		var id = $('.dialog-select-meters :selected').val();
		if(id > 0){
			$.get('get_meter_periods',{
				meter_id: id
				},function(r){
					init_content(r);
				});
		}
	});

	// запрашивает счетчики
	$('.dialog-select-service').change(function(){
		var service = $('.dialog-select-service :selected').val();
		if(service.length > 0){
			$.get('get_meter_options',{
				service: service
				},function(r){
					init_content(r);
				});
		}
	});

	// Привязывает счетчик к лицевому счету с выбранными параметрами
	$('.change_meter').click(function(){
		$.get('change_meter',{
			number_id: {{ number.get_id() }},
			meter_id: {{ meter.get_id() }},
			serial: {{ meter.get_serial() }},
			service: $('.dialog-select-service :selected').val(),
			new_meter_id: $('.dialog-select-meters :selected').val(),
			period: $('.dialog-select-periods :selected').val(),
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}