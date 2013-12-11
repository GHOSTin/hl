{% extends "dialog.tpl" %}
{% block title %}Диалог ошибки{% endblock title %}
{% block dialog %}
	<textarea class="dialog-send_error" style="width:90%"></textarea>
{% endblock dialog %}
{% block buttons %}
	<div class="btn send_error">Отправить</div>
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