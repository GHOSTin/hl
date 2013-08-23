{% extends "dialog.tpl" %}
{% set user = component.user %}
{% set group = component.group %}
{% block title %}Диалог исключения пользователя из группы{% endblock title %}
{% block dialog %}
	Исключить "{{ user.lastname }} {{ user.firstname }} {{ user.middlename }}" из группы "{{ group.name }}"
{% endblock dialog %}
{% block buttons %}
	<div class="btn exclude_user">Исключить</div>
{% endblock buttons %}
{% block script %}
	//Исключает пользователя из группы
	$('.exclude_user').click(function(){
		$.get('exclude_user',{
			group_id: {{ group.id }},
			user_id: {{ user.id }}
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}