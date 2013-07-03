{% extends "dialog.tpl" %}
{% set users = component.users %}
{% set group = component.group %}
{% block title %}Диалог нового пользователя пользователя{% endblock title %}
{% block dialog %}
	<select class="dialog-select-user_id">
		<option value="">Выберите пользователя...</option>
		{% for user in users %}
		<option value="{{ user.id }}">{{ user.lastname }} {{ user.firstname }} {{ user.lastname }}</option>
		{% endfor %}
	</select>
{% endblock dialog %}
{% block buttons %}
	<div class="btn add_user">Добавить</div>
{% endblock buttons %}
{% block script %}
	// Добавляет нового пользователя
	$('.add_user').click(function(){
		var user_id = $('.dialog-select-user_id').val();
		if(user_id > 0){
			$.get('add_user',{
				group_id: {{ group.id }},
				user_id: user_id
				},function(r){
					$('.dialog').modal('hide');
					init_content(r);
				});
		}
	});
{% endblock script %}