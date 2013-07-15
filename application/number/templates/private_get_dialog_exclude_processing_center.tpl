{% extends "dialog.tpl" %}
{% set center = component.center %}
{% set number = component.number %}
{% set identifier = component.identifier %}
{% block title %}Диалог исключения процессингового центра{% endblock title %}
{% block dialog %}
	Вы действительно хотите удалить идентификатор <b>{{ identifier }}</b> расчетного центра <b>{{ center.name }}</b>?
{% endblock dialog %}
{% block buttons %}
	<div class="btn exclude_processing_center">Удалить</div>
{% endblock buttons %}
{% block script %}
// Удаляет идентификатор в процессинговом центре
$('.exclude_processing_center').click(function(){
	$.get('exclude_processing_center',{
		number_id: {{ number.id }},
		center_id: {{ center.id }},
		identifier: {{ identifier }}
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
});
{% endblock script %}