{% extends "dialog.tpl" %}

{% block title %}Диалог создания нового пользователя{% endblock %}

{% block dialog %}
<div>
	<div class="form-group">
		<label>Фамилия</label>
		<input type="text" class="dialog-input-lastname form-control">
	</div>
	<div class="form-group">
		<label>Имя</label>
		<input type="text" class="dialog-input-firstname form-control">
	</div>
	<div class="form-group">
		<label>Отчество</label>
		<input type="text" class="dialog-input-middlename form-control">
	</div>
	<div class="form-group">
		<label>Логин</label>
		<input type="text" class="dialog-input-login form-control">
	</div>
	<div class="form-group">
		<label>Пароль</label>
		<input type="password" class="dialog-input-password form-control">
	</div>
	<div class="form-group">
		<label>Подтверждение пароля</label>
		<input type="password" class="dialog-input-confirm form-control">
	</div>
</div>
{% endblock %}

{% block buttons %}
	<div class="btn btn-primary create_user">Создать</div>
{% endblock %}

{% block script %}
	$('.create_user').click(function(){
		$.get('create_user',{
			lastname: $('.dialog-input-lastname').val(),
			firstname: $('.dialog-input-firstname').val(),
			middlename: $('.dialog-input-middlename').val(),
			login: $('.dialog-input-login').val(),
			password: $('.dialog-input-password').val(),
			confirm: $('.dialog-input-confirm').val()
		},function(res){
			$('.workspace').html(res);
			$('.dialog').modal('hide');
		});
	});
{% endblock %}