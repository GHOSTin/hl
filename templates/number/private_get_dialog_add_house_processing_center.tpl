{% extends "dialog.tpl" %}
{% set centers = response.centers %}
{% block title %}Диалог добавления процессингового центра{% endblock title %}
{% block dialog %}
	<div class="form-group">
		<select class="dialog-select-centers form-control">
			<option value="">Выберите процессинговый центр</option>
			{% for center in centers %}
			<option value="{{ center.get_id() }}">{{ center.get_name() }}</option>
			{% endfor %}
		</select>
	</div>
	<div class="form-group">
		<label>Идентификатор</label>
		<input type="text" class="dialog-input-identifier form-control">
	</div>
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-default add_house_processing_center">Добавить</div>
{% endblock buttons %}
{% block script %}
// Добавляет идентификатор в процессинговом центре
$('.add_house_processing_center').click(function(){
	$.get('add_house_processing_center',{
		house_id: {{ request.GET('id') }},
		center_id: $('.dialog-select-centers').val(),
		identifier: $('.dialog-input-identifier').val()
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
});
{% endblock script %}