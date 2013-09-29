{% extends "dialog.tpl" %}
{% set n2m = component.n2m %}
{% block title %}Диалог редактирования серийного номера счетчика{% endblock title %}
{% block dialog %}
	<input type="text" class="dialog-input-serial" value="{{ n2m.get_serial() }}">	
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_serial">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет серийный номер счетчика привязанного к лицевому счету
	$('.update_serial').click(function(){
		$.get('update_serial',{
			number_id: {{ n2m.get_number().get_id() }},
			meter_id: {{ n2m.get_meter().get_id() }},
			serial: '{{ n2m.get_serial() }}',
			new_serial: $('.dialog-input-serial').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}