{% extends "dialog.tpl" %}
{% block title %}Диалог создания новой услуги{% endblock title %}
{% block dialog %}
	<input type="input" class="dialog-input-name">
	<p>В названии услуги могут быть использованы буквы русского алфавита, цифры, пробелы.</p>
{% endblock dialog %}
{% block buttons %}
	<div class="btn create_service">Создать</div>
{% endblock buttons %}
{% block script %}
	$('.create_service').click(function(){
		$.get('create_service',{
			name: $('.dialog-input-name').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}