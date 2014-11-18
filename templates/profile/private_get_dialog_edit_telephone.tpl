{% extends "dialog.tpl" %}
{% set user = response.user %}
{% block script %}
	$('.update_telephone').click(function(){
		$.get('update_telephone',{
			telephone: $('.dialog-telephone').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock script %}
{% block title %}Смена номера телефона{% endblock title %}
{% block dialog %}
	<input type="text" value="{{ user.get_telephone() }}" class="dialog-telephone form-control">
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-default update_telephone">Сохранить</div>
{% endblock buttons %}