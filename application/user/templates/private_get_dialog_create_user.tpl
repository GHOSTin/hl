{% extends "dialog.tpl" %}
{% block title %}Диалог создания нового пользователя{% endblock title %}
{% block dialog %}
<table>
	<tr>
		<td>Фамилия</td>
		<td>
			<input type="text" class="dialog-input-lastname">
		</td>
	</tr>
	<tr>
		<td>Имя</td>
		<td>
			<input type="text" class="dialog-input-firstname">
		</td>
	</tr>
	<tr>
		<td>Отчество</td>
		<td>
			<input type="text" class="dialog-input-middlename">
		</td>
	</tr>
	<tr>
		<td>Логин</td>
		<td>
			<input type="text" class="dialog-input-login">
		</td>
	</tr>
	<tr>
		<td>Пароль</td>
		<td>
			<input type="text" class="dialog-input-password">
		</td>
	</tr>
	<tr>
		<td>Подтверждение</td>
		<td>
			<input type="text" class="dialog-input-confirm">
		</td>
	</tr>
</table>
{% endblock dialog %}
{% block buttons %}
	<div class="btn create_user">Создать</div>
{% endblock buttons %}
{% block script %}
	// Создает нового пользователя
	$('.create_user').click(function(){
		$.get('create_user',{
			lastname: $('.dialog-input-lastname').val(),
			firstname: $('.dialog-input-firstname').val(),
			middlename: $('.dialog-input-middlename').val(),
			login: $('.dialog-input-login').val(),
			password: $('.dialog-input-password').val(),
			confirm: $('.dialog-input-confirm').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}