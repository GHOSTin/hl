{% extends "dialog.tpl" %}

{% block title %}Диалог редактирования сотового телефона владельца{% endblock %}

{% block dialog %}
	<input type="text" class="dialog-input-cellphone form-control" value="{{ number.get_cellphone() }}">
{% endblock %}

{% block buttons %}
	<div class="btn btn-primary update_number_cellphone">Сохранить</div>
{% endblock %}

{% block script %}
	// Изменяет телефон владельца лицевого счета
	$('.update_number_cellphone').click(function(){
		$.get('update_number_cellphone',{
			id: {{ number.get_id() }},
			cellphone: $('.dialog-input-cellphone').val()
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
	});
{% endblock %}