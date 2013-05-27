{% extends "dialog.tpl" %}
{% set meter = component.meters[0] %}
{% set services = component.services %}
{% block title %}Диалог добавления услуги{% endblock title %}
{% block dialog %}
	<select class="dialog-select-service">
		<option value="0">Выберите услугу</option>
	{% for service in services %}
		<option value="{{ service.id }}">{{ service.name }}</opyion>
	{% endfor %}
	</select>
{% endblock dialog %}
{% block buttons %}
	<div class="btn add_service">Добавить</div>
{% endblock buttons %}
{% block script %}
	$('.add_service').click(function(){
		$.get('add_service',{
			id: {{ meter.id }},
			service_id: $('.dialog-select-service').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}