{% extends "dialog.tpl" %}

{% block title %}Диалог добавления пользователя{% endblock title %}

{% block dialog %}
	<select class="dialog-select-user_id form-control">
		<option value="">Выберите пользователя...</option>
		{% for user in users %}
		<option value="{{ user.get_id() }}">{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}</option>
		{% endfor %}
	</select>
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary add_user">Добавить</div>
{% endblock buttons %}

{% block script %}
	// Добавляет нового пользователя
	$('.add_user').click(function(){
		var user_id = $('.dialog-select-user_id').val();
		if(user_id > 0){
			$.get('add_user',{
				group_id: {{ group.get_id() }},
				user_id: user_id
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
		}
	});
{% endblock script %}