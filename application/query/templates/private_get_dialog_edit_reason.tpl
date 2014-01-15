{% extends "dialog.tpl" %}
{% set query = component.query %}
{% block title %}Изменение причины закрытия{% endblock title %}
{% block dialog %}
	<textarea class="dialog-description form-control">{{ query.get_close_reason() }}</textarea>
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary update_reason">Сохранить</div>
{% endblock buttons %}
{% block script %}
	$('.update_reason').click(function(){
		$.get('update_reason',{
			id: {{ query.get_id() }},
			reason: $('.dialog-description').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock script %}