{% extends "dialog.tpl" %}
{% set group = component.group %}
{% block title %}Диалог редактирования названия группы{% endblock title %}
{% block dialog %}
	<label>Название группы</label>
	<input type="text" class="dialog-input-name" value="{{ group.name }}">
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_group_name">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет название группы
	$('.update_group_name').click(function(){
		$.get('update_group_name',{
			id: {{ group.id }},
			name: $('.dialog-input-name').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}