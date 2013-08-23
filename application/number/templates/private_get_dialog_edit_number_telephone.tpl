{% extends "dialog.tpl" %}
{% set number = component.number %}
{% block title %}Диалог редактирования телефона владельца{% endblock title %}
{% block dialog %}
	<input type="text" class="dialog-input-telephone form-control" value="{{ number.telephone }}">
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary update_number_telephone">Изменить</div>
{% endblock buttons %}
{% block script %}
	// Изменяет телефон владельца лицевого счета
	$('.update_number_telephone').click(function(){
		$.get('update_number_telephone',{
			id: {{ number.id }},
			telephone: $('.dialog-input-telephone').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}