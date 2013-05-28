{% extends "dialog.tpl" %}
{% set meter = component.meters[0] %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block title %}Диалог редактирования тарифности{% endblock title %}
{% block dialog %}
	<select class="dialog-input-rates">
		{% for key, rate in rates %}
			<option value="{{ key + 1 }}"
			{% if meter.rates == (key + 1)%} selected{% endif %}
			>{{ rate }}</option>
		{% endfor %}
	</select>
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_rates">Сохранить</div>
{% endblock buttons %}
{% block script %}
	$('.update_rates').click(function(){
		$.get('update_rates',{
			id: {{ meter.id }},
			rates: $('.dialog-input-rates').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}