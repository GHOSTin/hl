{% extends "dialog.tpl" %}
{% set house = component.house %}
{% set center = house.get_processing_centers()[request.GET('center_id')] %}
{% block title %}Диалог добавления процессингового центра{% endblock title %}
{% block dialog %}
	Вы действительно хотите удалить привязку дома к процессинговому центру <b>{{ center.get_name() }}</b> с идентификатором <b>{{ center.get_identifier() }}</b>?
{% endblock dialog %}
{% block buttons %}
	<div class="btn remove_house_processing_center">Удалить</div>
{% endblock buttons %}
{% block script %}
// Удаляет идентификатор в процессинговом центре
$('.remove_house_processing_center').click(function(){
	$.get('remove_house_processing_center',{
		house_id: {{ house.get_id() }},
		center_id: {{center.get_id()}}
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
});
{% endblock script %}