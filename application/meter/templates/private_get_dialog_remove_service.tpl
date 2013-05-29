{% extends "dialog.tpl" %}
{% set meter = component.meter %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% block title %}Диалог исключения услуги{% endblock title %}
{% block dialog %}
	Удалить услугу "{{ services[meter.service[0]] }}" из счетчика?
{% endblock dialog %}
{% block buttons %}
	<div class="btn remove_service">Исключить</div>
{% endblock buttons %}
{% block script %}
	$('.remove_service').click(function(){
		$.get('remove_service',{
			id: {{ meter.id }},
			service: '{{ meter.service[0] }}'
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}