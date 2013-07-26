{% extends "dialog.tpl" %}
{% set user = component.users[0] %}
{% block title %}Диалог редактирования пароля пользователя{% endblock title %}
{% block dialog %}
	<label>Логин</label>
	<input type="text" class="dialog-input-login" value="{{ user.login }}">
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_login">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет логин пользователя
	$('.update_login').click(function(){
		$.get('update_login',{
			id: {{ user.id }},
			login: $('.dialog-input-login').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}