{% extends "dialog.tpl" %}
{% set number = response.number %}
{% block title %}Диалог добавления счетчика{% endblock title %}
{% block dialog %}
	<select class="dialog-select-service form-control">
		<option value="">Выберите услугу</option>
		<option value="cold_water">Холодное водоснабжение</option>
		<option value="hot_water">Горячее водоснабжение</option>
		<option value="electrical">Электроэнергия</option>
	</select>
	<select class="dialog-select-meters form-control" disabled>
		<option>Ожидание...</option>
	</select>
{% endblock dialog %}
{% block script %}
	// изменяет диалог
	$('.dialog-select-meters').change(function(){
		var id = $('.dialog-select-meters :selected').val();
		if(id > 0){
			$.get('get_dialog_add_meter_option',{
				number_id: {{ request.GET('id') }},
				meter_id: id,
				service: $('.dialog-select-service :selected').val()
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
{% endblock script %}