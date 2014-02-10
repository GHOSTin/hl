{% extends "dialog.tpl" %}
{% set user = component.user %}
{% block script %}
	$('.update_password').click(function(){
		$.get('update_password',{
			new_password: $('.dialog-new_password').val(),
			confirm_password: $('.dialog-confirm_password').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock script %}
{% block title %}Смена пароля{% endblock title %}
{% block dialog %}
	<p>
		Сменить пароль пользователю "{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}"?
	</p>
	<div class="form-group">
		<label>Новый пароль</label>
		<input type="password" class="form-control dialog-new_password" required>
	</div>
	<div class="form-group">
		<label>Подтверждение</label>
		<input type="password" class="form-control dialog-confirm_password" required>
	</div>
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-default update_password">Сохранить</div>
{% endblock buttons %}