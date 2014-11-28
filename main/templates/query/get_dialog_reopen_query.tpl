{% extends "ajax.tpl" %}

{% block js %}
	show_dialog(get_hidden_content());
	$('.reopen_query').click(function(){
		$.get('reopen_query',{
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
        <h3>Диалог переоткрытия заявки</h3>
    </div>
	<div class="modal-body">
		Переоткрыть заявку?
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary reopen_query">Переоткрыть</div>
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>
</div>
{% endblock html %}