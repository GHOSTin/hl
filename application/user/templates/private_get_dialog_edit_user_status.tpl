{% extends "dialog.tpl" %}
{% set user = component.users[0] %}
{% block title %}Диалог изменения статуса пользователя{% endblock title %}
{% block dialog %}
	{% if user.status == 'false' %}
		Вы хотите активировать пользователя?
	{% elseif user.status == 'true' %}
		Вы хотите заблокировать пользователя?
	{% endif %}
{% endblock dialog %}
{% block buttons %}
	{% if user.status == 'false' %}
		<div class="btn update_user_status">Активировать</div>
	{% elseif user.status == 'true' %}
		<div class="btn update_user_status">Заблокировать</div>
	{% endif %}
{% endblock buttons %}
{% block script %}
	// Изменяет статус пользователя
	$('.update_user_status').click(function(){
		$.get('update_user_status',{
			id: {{ user.id }}
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}