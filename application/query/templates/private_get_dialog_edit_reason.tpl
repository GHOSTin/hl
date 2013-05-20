{% extends "dialog.tpl" %}
{% set query = component.queries[0] %}
{% block script %}
	show_dialog(get_hidden_content());
	$('.update_reason').click(function(){
		$.get('update_reason',{
			id: {{query.id}},
			reason: $('.dialog-description').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock script %}
{% block title %}Изменение причины закрытия{% endblock title %}
{% block dialog %}
	<textarea class="dialog-description" style="width:500px; height:100px;">{{query.close_reason}}</textarea>
{% endblock dialog %}
{% block buttons %}
	<div class="btn update_reason">Сохранить</div>
{% endblock buttons %}