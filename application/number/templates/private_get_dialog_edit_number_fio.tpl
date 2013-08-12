{% extends "dialog.tpl" %}
{% set number = component.number %}
{% block title %}Диалог редактирования ФИО владельца{% endblock title %}
{% block dialog %}
	<input type="text" class="dialog-input-fio" value="{{ number.fio }}">
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_number_fio">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет ФИО владельца лицевого счета
	$('.update_number_fio').click(function(){
		$.get('update_number_fio',{
			id: {{ number.id }},
			fio: $('.dialog-input-fio').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}