{% extends "dialog.tpl" %}
{% set n2m = component.n2m %}
{% block title %}Диалог удаления привязки счетчика{% endblock title %}
{% block dialog %}
Вы действительно хотите удалить привязку счетчика к лицевому счете, а также показания?
{% endblock dialog %}
{% block buttons %}
	<div class="btn delete_meter">Удалить</div>
{% endblock buttons %}
{% block script %}
	// Удаляет счетчик из лицевого счета
	$('.delete_meter').click(function(){
		$.get('delete_meter',{
			number_id: {{ n2m.get_number().get_id() }},
			meter_id: {{ n2m.get_meter().get_id() }},
			serial: '{{ n2m.get_serial() }}'
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}