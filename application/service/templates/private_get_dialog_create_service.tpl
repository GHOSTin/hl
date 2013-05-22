{% extends "dialog.tpl" %}
{% set query = component.queries[0] %}
{% block script %}
	$('.create_service').click(function(){
		$.get('create_service',{
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock script %}
{% block title %}Диалог создания новой услуги{% endblock title %}
{% block dialog %}
	<input type="dialog-input-name">
{% endblock dialog %}
{% block buttons %}
	<div class="btn create_service">Создать</div>
{% endblock buttons %}