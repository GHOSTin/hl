{% extends "dialog.tpl" %}
{% set meter = component.meter %}
{% block title %}Диалог добавления периода{% endblock title %}
{% block dialog %}
	<input type="text" class="dialog-input-month">
{% endblock dialog %}
{% block buttons %}
	<div class="btn add_period">Добавить</div>
{% endblock buttons %}
{% block script %}
	$('.add_period').click(function(){
		$.get('add_period',{
			id: {{ meter.id }},
			period: $('.dialog-input-month').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}