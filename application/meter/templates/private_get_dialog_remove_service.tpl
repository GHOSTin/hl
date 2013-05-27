{% extends "dialog.tpl" %}
{% set meter = component.meters[0] %}
{% set service = component.services[0] %}
{% block title %}Диалог исключения услуги{% endblock title %}
{% block dialog %}
	Удалить услугу "{{ service.name }}" из счетчика "{{ meter.name }}"?
{% endblock dialog %}
{% block buttons %}
	<div class="btn remove_service">Исключить</div>
{% endblock buttons %}
{% block script %}
	$('.remove_service').click(function(){
		$.get('remove_service',{
			meter_id: {{ meter.id }},
			service_id: {{ service.id }}
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}