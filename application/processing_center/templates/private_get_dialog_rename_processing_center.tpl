{% extends "dialog.tpl" %}
{% set center = component.center %}
{% block title %}Диалог переименования процессингового центра{% endblock title %}
{% block dialog %}
	<input type="text" class="dialog-input-name" value="{{ center.get_name() }}">
{% endblock dialog %}
{% block buttons %}
	<div class="btn rename_processing_center">Создать</div>
{% endblock buttons %}
{% block script %}
	// Переименовывает процессинговый центр
	$('.rename_processing_center').click(function(){
		$.get('rename_processing_center',{
			id: {{ center.get_id() }},
			name: $('.dialog-input-name').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}