{% extends "dialog.tpl" %}
{% set service = component.services[0] %}
{% block title %}Диалог переименования услуги{% endblock title %}
{% block dialog %}
	<input type="input" class="dialog-input-name" value="{{ service.name }}">
	<p>В названии услуги могут быть использованы буквы русского алфавита, цифры, пробелы.</p>
{% endblock dialog %}
{% block buttons %}
	<div class="btn rename_service">Переименовать</div>
{% endblock buttons %}
{% block script %}
	$('.rename_service').click(function(){
		$.get('rename_service',{
			id: {{ service.id }},
			name: $('.dialog-input-name').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}