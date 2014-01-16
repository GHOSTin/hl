{% extends "dialog.tpl" %}
{% block title %}Добавление комментария{% endblock title %}
{% block dialog %}
	<textarea class="dialog-message" style="width:500px; height:100px;"></textarea>
{% endblock dialog %}
{% block buttons %}
	<div class="btn add_comment">Сохранить</div>
{% endblock buttons %}
{% block script %}
	$('.add_comment').click(function(){
		$.get('add_comment',{
			query_id: {{ request.GET('id') }},
			message: $('.dialog-message').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock script %}