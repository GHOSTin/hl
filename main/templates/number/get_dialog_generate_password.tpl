{% extends "dialog.tpl" %}

{% block title %}Диалог генерирования пароля{% endblock %}

{% block dialog %}
	Сгенерировать и отправить пароль на почту?
{% endblock %}

{% block buttons %}
	<div class="btn btn-primary generate_password">Генерировать</div>
{% endblock %}

{% block script %}
	$('.generate_password').click(function(){
		$.get('/numbers/{{ number.get_id() }}/generate_password/',
    function(r){
			$('.dialog').modal('hide');
		});
	});
{% endblock %}