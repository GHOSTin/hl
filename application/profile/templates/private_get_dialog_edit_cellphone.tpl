{% extends "dialog.tpl" %}
{% set user = component.user %}
{% block script %}
	$('.update_cellphone').click(function(){
		$.get('update_cellphone',{
			cellphone: $('.dialog-cellphone').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock script %}
{% block title %}Смена номера сотового телефона{% endblock title %}
{% block dialog %}
		<input type="text" value="{{ user.get_cellphone() }}" class="dialog-cellphone form-control">
{% endblock dialog %}
{% block buttons %}
		<div class="btn btn-default update_cellphone">Сохранить</div>
{% endblock buttons %}
