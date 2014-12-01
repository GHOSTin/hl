{% extends "dialog.tpl" %}

{% block title %}Добавление комментария{% endblock title %}

{% block dialog %}
<div class="form-group">
	<textarea class="dialog-message form-control" style="width:500px; height:100px;"></textarea>
</div>
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-default add_comment">Сохранить</div>
{% endblock buttons %}

{% block script %}
	$('.add_comment').click(function(){
		$.get('add_comment',{
			query_id: {{ query.get_id() }},
			message: $('.dialog-message').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock script %}