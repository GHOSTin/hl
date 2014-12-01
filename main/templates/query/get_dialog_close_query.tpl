{% extends "ajax.tpl" %}

{% block js %}
	show_dialog(get_hidden_content());
	$('.close_query').click(function(){
		$.get('close_query',{
			id: {{ query.get_id() }},
			reason: $('.dialog-reason').val()
		},function(r){
			init_content(r);
			$('.dialog').modal('hide');
		});
	});
{% endblock js %}

{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>Описания заявки</h3>
    </div>
	<div class="modal-body">
		<textarea class="dialog-reason form-control" rows="5"></textarea>
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary close_query">Сохранить</div>
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>
</div>
{% endblock html %}