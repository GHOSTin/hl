{% extends "dialog.tpl" %}
{% set meter = component.meter %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% block title %}Диалог исключения услуги{% endblock title %}
{% block dialog %}
	Удалить услугу "{{ services[request.GET('service')] }}" из счетчика?
{% endblock dialog %}
{% block buttons %}
	<div class="btn remove_service">Исключить</div>
{% endblock buttons %}
{% block script %}
	$('.remove_service').click(function(){
		$.get('remove_service',{
			id: {{ request.GET('id') }},
			service: '{{ request.GET('service') }}'
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}