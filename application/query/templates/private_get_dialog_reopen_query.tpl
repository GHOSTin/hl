{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.reopen_query').click(function(){
		$.get('reopen_query',{
			id: {{query.id}},
			reason: $('.dialog-reason').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Диалог переоткрытия заявки</h3>
    </div>	
	<div class="modal-body">
		Переоткрыть заявку?
	</div>
	<div class="modal-footer">
		<div class="btn reopen_query">Переоткрыть</div>
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}