{% extends "dialog.tpl" %}
{% set centers = component.centers %}
{% set number = component.number %}
{% block title %}Диалог добавления процессингового центра{% endblock title %}
{% block dialog %}
	<select class="dialog-select-centers form-control">
		<option value="">Выберите процессинговый центр</option>
		{% for center in centers %}
		<option value="{{ center.id }}">{{ center.name }}</option>
		{% endfor %}
	</select>
	<div>
		<label>Идентификатор</label>
		<input type="text" class="dialog-input-identifier form-control col-6">
	</div>
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary add_processing_center">Добавить</div>
{% endblock buttons %}
{% block script %}
// Добавляет идентификатор в процессинговом центре
$('.add_processing_center').click(function(){
	$.get('add_processing_center',{
		number_id: {{ number.id }},
		center_id: $('.dialog-select-centers').val(),
		identifier: $('.dialog-input-identifier').val()
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
});
{% endblock script %}