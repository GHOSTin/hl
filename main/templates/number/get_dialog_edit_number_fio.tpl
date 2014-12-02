{% extends "dialog.tpl" %}

{% block title %}Диалог редактирования ФИО владельца{% endblock %}

{% block dialog %}
	<input type="text" class="dialog-input-fio form-control" value="{{ number.get_fio() }}">
{% endblock %}

{% block buttons %}
	<div class="btn btn-primary update_number_fio">Сохранить</div>
{% endblock %}

{% block script %}
	// Изменяет ФИО владельца лицевого счета
	$('.update_number_fio').click(function(){
		$.get('update_number_fio',{
			id: {{ number.get_id() }},
			fio: $('.dialog-input-fio').val()
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
	});
{% endblock %}