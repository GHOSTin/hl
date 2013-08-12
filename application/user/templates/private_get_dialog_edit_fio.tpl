{% extends "dialog.tpl" %}
{% set user = component.user %}
{% block title %}Диалог редактирования ФИО пользователя{% endblock title %}
{% block dialog %}
	<ul class="unstyled">
		<li>
			<label>Фамилия</label>
			<input type="text" class="dialog-input-lastname" value="{{ user.lastname }}">
		</li>
		<li>
			<label>Имя</label>
			<input type="text" class="dialog-input-firstname" value="{{ user.firstname }}">
		</li>
		<li>
			<label>Отчество</label>
			<input type="text" class="dialog-input-middlename" value="{{ user.middlename }}">
		</li>
	</ul>
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_fio">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет ФИО пользователя
	$('.update_fio').click(function(){
		$.get('update_fio',{
			id: {{ user.id }},
			lastname: $('.dialog-input-lastname').val(),
			firstname: $('.dialog-input-firstname').val(),
			middlename: $('.dialog-input-middlename').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}