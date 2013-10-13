{% extends "dialog.tpl" %}
{% block title %}Диалог редактирования пароля в личный кабинет{% endblock title %}
{% block dialog %}
	<div>
	Пароль: <input type="password" class="dialog-input-password">
	</div>
	<div>
	Подтверждение: <input type="password" class="dialog-input-confirm">
	</div>
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_number_fio">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет пароль в личный кабинет
	$('.update_number_fio').click(function(){
		$.get('update_number_password',{
			id: {{ request.GET('id') }},
			password: $('.dialog-input-password').val(),
			confirm: $('.dialog-input-confirm').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}