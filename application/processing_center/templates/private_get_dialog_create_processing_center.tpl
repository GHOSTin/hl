{% extends "dialog.tpl" %}
{% block title %}Диалог создания нового процессингового центра{% endblock title %}
{% block dialog %}
	<input type="text" class="dialog-input-name">
{% endblock dialog %}
{% block buttons %}
	<div class="btn create_processing_center">Создать</div>
{% endblock buttons %}
{% block script %}
	// Создает новый процессинговый центр
	$('.create_processing_center').click(function(){
		$.get('create_processing_center',{
			name: $('.dialog-input-name').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}