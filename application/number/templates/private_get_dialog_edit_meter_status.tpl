{% extends "dialog.tpl" %}
{% set n2m = component.n2m %}
{% block title %}Диалог редактирования статуса счетчика{% endblock title %}
{% block dialog %}
	{% if n2m.get_status() == 'enabled' %}
		Вы действительно хотите закрыть счетчик?
	{% endif %}
	{% if n2m.get_status() == 'disabled' %}
		Вы действительно хотите открыть счетчик?
	{% endif %}
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_meter_status">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет период поверки счетчика привязанного к лицевому счету
	$('.update_meter_status').click(function(){
		$.get('update_meter_status',{
			number_id: {{ n2m.get_number().get_id() }},
			meter_id: {{ n2m.get_meter().get_id() }},
			serial: '{{ n2m.get_serial() }}',
			{% if n2m.get_status() == 'enabled' %}
				status: 'disabled'
			{% endif %}
			{% if n2m.get_status() == 'disabled' %}
				status: 'enabled'
			{% endif %}
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}