{% extends "dialog.tpl" %}

{% block title %}Диалог редактирования пароля пользователя{% endblock title %}

{% block dialog %}
	<ul class="list-unstyled">
		<li>
			<label>Пароль</label>
			<input type="password" class="dialog-input-password  form-control">
		</li>
		<li>
			<label>Подтверждение пароля</label>
			<input type="password" class="dialog-input-confirm  form-control">
		</li>
	</ul>
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary update_password">Изменить</div>
{% endblock buttons %}

{% block script %}
	// Изменяет пароля пользователя
	$('.update_password').click(function(){
		$.get('update_password',{
			id: {{ user.get_id() }},
			password: $('.dialog-input-password').val(),
			confirm: $('.dialog-input-confirm').val()
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
	});
{% endblock script %}