{% extends "dialog.tpl" %}

{% block title %}Диалог редактирования логина пользователя{% endblock title %}

{% block dialog %}
	<label>Логин</label>
	<input type="text" class="dialog-input-login form-control" value="{{ user.get_login() }}">
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary update_login">Изменить</div>
{% endblock buttons %}

{% block script %}
	// Изменяет логин пользователя
	$('.update_login').click(function(){
		$.get('update_login',{
			id: {{ user.get_id() }},
			login: $('.dialog-input-login').val()
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
	});
{% endblock script %}