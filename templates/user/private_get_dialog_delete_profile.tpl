{% extends "dialog.tpl" %}
{% block title %}Диалог удаления профиля{% endblock title %}
{% block dialog %}
	Вы действительно хотите удалить профиль?
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary delete_profile">Удалить</div>
{% endblock buttons %}
{% block script %}
	// Удаляет профиль
	$('.delete_profile').click(function(){
		$.get('delete_profile',{
			user_id: {{ request.take_get('user_id') }},
			profile: '{{ request.take_get('profile') }}'
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}