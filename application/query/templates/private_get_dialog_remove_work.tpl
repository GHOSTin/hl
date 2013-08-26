{% extends "ajax.tpl" %}
{% set query = component.query %}
{% set work = component.work %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.remove_work').click(function(){
		$.get('remove_work',{
			id: {{query.id}},
			work_id: {{work.id}}
			},function(r){
				$('.query[query_id = {{query.id}}] .query-works .works li[work={{work.id}}]').remove();
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}
{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>Удаление работы</h3>
    </div>	
	<div class="modal-body">
		Удалить из заявки работу "{{work.name}}"?
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary remove_work">Сохранить</div>
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}