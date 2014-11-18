{% extends "dialog.tpl" %}
{% block title %}Диалог ошибки{% endblock title %}
{% block dialog %}
<form role="form">
	<textarea class="form-control dialog-send_error" placeholder="Опишите произошедшую ошибку."></textarea>
</form>
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-default send_error">Отправить</div>
{% endblock buttons %}
{% block script %}
// Добавляет идентификатор в процессинговом центре
$('.send_error').click(function(){
	$.get('/error/send_error',{
		text: $('.dialog-send_error').val()
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
});
{% endblock script %}