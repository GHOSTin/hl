{% extends "dialog.tpl" %}
{% set user = response.user %}
{% block title %}Диалог редактирования ФИО пользователя{% endblock title %}
{% block dialog %}
	<ul class="list-unstyled">
		<li>
			<label>Фамилия</label>
			<input type="text" class="dialog-input-lastname form-control" value="{{ user.get_lastname() }}">
		</li>
		<li>
			<label>Имя</label>
			<input type="text" class="dialog-input-firstname form-control" value="{{ user.get_firstname() }}">
		</li>
		<li>
			<label>Отчество</label>
			<input type="text" class="dialog-input-middlename form-control" value="{{ user.get_middlename() }}">
		</li>
	</ul>
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary update_fio">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет ФИО пользователя
	$('.update_fio').click(function(){
		$.get('update_fio',{
			id: {{ user.get_id() }},
			lastname: $('.dialog-input-lastname').val(),
			firstname: $('.dialog-input-firstname').val(),
			middlename: $('.dialog-input-middlename').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}