{% extends "dialog.tpl" %}
{% block title %}Диалог создания новой группы{% endblock title %}
{% block dialog %}
	<input type="text" class="dialog-input-name form-control">
	<p class="help-block">Название группы может содержать символы русского алфавита и цифры.</p>
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary create_group">Создать</div>
{% endblock buttons %}
{% block script %}
	// Создает новую группу
	$('.create_group').click(function(){
		$.get('create_group',{
			name: $('.dialog-input-name').val(),
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}