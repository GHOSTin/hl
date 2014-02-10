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
	<dl class="dl-horizontal">
		<dt>Новый пароль</dt>
		<dd><input type="text" class="dialog-new_password"></dd>
		<dt>Подтверждение</dt>
		<dd><input type="text" class="dialog-confirm_password"></dd>
	</dl>
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_password">Сохранить</div>
{% endblock buttons %}