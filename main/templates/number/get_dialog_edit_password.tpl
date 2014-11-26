{% extends "dialog.tpl" %}

{% block title %}Диалог редактирования пароля в личный кабинет{% endblock %}

{% block dialog %}
	<div class="form-group">
		<label>Пароль:</label>
		<input type="password" class="dialog-input-password form-control">
	</div>
	<div class="form-group">
		<label>Подтверждение:</label>
		<input type="password" class="dialog-input-confirm form-control">
	</div>
{% endblock %}

{% block buttons %}
	<div class="btn btn-primary update_number_fio">Сохранить</div>
{% endblock %}

{% block script %}
	// Изменяет пароль в личный кабинет
	$('.update_number_fio').click(function(){
		$.get('update_number_password',{
			id: {{ number.get_id() }},
			password: $('.dialog-input-password').val(),
			confirm: $('.dialog-input-confirm').val()
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
	});
{% endblock %}