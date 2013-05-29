{% extends "dialog.tpl" %}
{% set number = component.numbers[0] %}
{% block title %}Диалог добавления счетчика{% endblock title %}
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
{% endblock dialog %}
{% block script %}
	// добавляет счетчик
	$('.add_meter').click(function(){
		$.get('add_meter',{
			id: {{ number.id }},
			service_id: $('.dialog-select-service').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
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
{% endblock script %}