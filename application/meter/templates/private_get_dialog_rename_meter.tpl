{% extends "dialog.tpl" %}
{% set meter = component.meter %}
{% block title %}Диалог переименования счетчика{% endblock title %}
{% block dialog %}
	<input type="input" class="dialog-input-name" value="{{ meter.name }}">
	<p>В названии счетчика могут быть использованы буквы русского алфавита, цифры, пробелы.</p>
{% endblock dialog %}
{% block buttons %}
	<div class="btn rename_meter">Переименовать</div>
{% endblock buttons %}
{% block script %}
	$('.rename_meter').click(function(){
		$.get('rename_meter',{
			id: {{ meter.id }},
			name: $('.dialog-input-name').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}