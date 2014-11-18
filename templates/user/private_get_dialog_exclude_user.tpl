{% extends "dialog.tpl" %}
{% set user = response.user %}
{% set group = response.group %}
{% block title %}Диалог исключения пользователя из группы{% endblock title %}
{% block dialog %}
	Исключить "{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}" из группы "{{ group.get_name() }}"
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary exclude_user">Исключить</div>
{% endblock buttons %}
{% block script %}
	//Исключает пользователя из группы
	$('.exclude_user').click(function(){
		$.get('exclude_user',{
			group_id: {{ group.get_id() }},
			user_id: {{ user.get_id() }}
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}