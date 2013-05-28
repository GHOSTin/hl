{% extends "dialog.tpl" %}
{% set meter = component.meters[0] %}
{% block title %}Диалог редактирования разрядности{% endblock title %}
{% block dialog %}
	<select class="dialog-input-capacity">
		{% for value in 1..9 %}
			<option value="{{ value }}"
			{% if meter.capacity == value %} selected{% endif %}
			>{{ value }}</option>
		{% endfor %}
	</select>
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_capacity">Сохранить</div>
{% endblock buttons %}
{% block script %}
	$('.update_capacity').click(function(){
		$.get('update_capacity',{
			id: {{ meter.id }},
			capacity: $('.dialog-input-capacity').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}