{% extends "dialog.tpl" %}
{% set n2m = component.n2m %}
{% block title %}Диалог редактирования комментария счетчика{% endblock title %}
{% block dialog %}
	<textarea class="dialog-input-comment" style="width:90%">{{ n2m.get_comment() }}</textarea>
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_meter_comment">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет комментарий счетчика привязанного к лицевому счету
	$('.update_meter_comment').click(function(){
		$.get('update_meter_comment',{
			number_id: {{ n2m.get_number().get_id() }},
			meter_id: {{ n2m.get_meter().get_id() }},
			serial: '{{ n2m.get_serial() }}',
			comment: $('.dialog-input-comment').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}