{% extends "dialog.tpl" %}
{% block title %}Диалог создания нового счетчика{% endblock title %}
{% block dialog %}
	<input type="input" class="dialog-input-name">
	<p>В названии счетчика могут быть использованы буквы русского алфавита, цифры, пробелы.</p>
{% endblock dialog %}
{% block buttons %}
	<div class="btn create_meter">Создать</div>
{% endblock buttons %}
{% block script %}
	$('.create_meter').click(function(){
		$.get('create_meter',{
			name: $('.dialog-input-name').val()
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}