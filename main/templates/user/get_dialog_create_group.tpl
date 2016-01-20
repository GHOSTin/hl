{% extends "dialog.tpl" %}

{% block title %}Диалог создания группы{% endblock %}

{% block dialog %}
	<input type="text" class="dialog-input-name form-control">
	<p class="help-block">Название группы может содержать символы русского алфавита и цифры.</p>
{% endblock %}

{% block buttons %}
	<div class="btn btn-primary create_group">Создать</div>
{% endblock %}

{% block script %}
	// Создает новую группу
	$('.create_group').click(function(){
		$.get('create_group',{
			name: $('.dialog-input-name').val(),
		},function(res){
			$('.workspace').html(res);
			$('.dialog').modal('hide');
		});
	});
{% endblock %}