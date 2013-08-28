{% extends "dialog.tpl" %}
{% set house = component.house %}
{% set center_id = component.center_id %}
{% set center = house.get_processing_centers()[center_id][0] %}
{% set identifier = house.get_processing_centers()[center_id][1] %}
{% block title %}Диалог добавления процессингового центра{% endblock title %}
{% block dialog %}
	Вы действительно хотите удалить привязку дома к процессинговому центру <b>{{ center.name}}</b> с идентификатором <b>{{ identifier }}</b>?
{% endblock dialog %}
{% block buttons %}
	<div class="btn remove_house_processing_center">Удалить</div>
{% endblock buttons %}
{% block script %}
// Удаляет идентификатор в процессинговом центре
$('.remove_house_processing_center').click(function(){
	$.get('remove_house_processing_center',{
		house_id: {{ house.id }},
		center_id: {{center_id}}
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
});
{% endblock script %}