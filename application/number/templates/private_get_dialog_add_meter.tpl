{% extends "dialog.tpl" %}
{% set number = component.numbers[0] %}
{% set services = component.services %}
{% block title %}Диалог добавления счетчика{% endblock title %}
{% block dialog %}
	<select class="dialog-select-service">
		<option value="0">Выберите услугу</option>
	{% for service in services %}
		<option value="{{ service.id }}">{{ service.name }}</opyion>
	{% endfor %}
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
		var service_id = $('.dialog-select-service :selected').val();
		if(service_id > 0){
			$.get('get_meter_options',{
				id: service_id
				},function(r){
					init_content(r);
				});
		}
	});
{% endblock script %}