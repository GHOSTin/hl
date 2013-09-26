{% extends "dialog.tpl" %}
{% set meter = component.meter %}
{% set services = component.services %}
{% block title %}Диалог добавления услуги{% endblock title %}
{% block dialog %}
	<select class="dialog-select-service">
		<option value="">Выберите услугу</option>
		<option value="cold_water">Холодное водоснабжение</option>
		<option value="hot_water">Горячее водоснабжение</option>
		<option value="electrical">Электроэнергия</option>
	</select>
{% endblock dialog %}
{% block buttons %}
	<div class="btn add_service">Добавить</div>
{% endblock buttons %}
{% block script %}
	$('.add_service').click(function(){
		$.get('add_service',{
			id: {{ meter.get_id() }},
			service: $('.dialog-select-service').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}