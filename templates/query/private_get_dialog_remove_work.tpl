{% extends "ajax.tpl" %}
{% set work = response.work %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.remove_work').click(function(){
		$.get('remove_work',{
			id: {{ request.GET('id') }},
			work_id: {{ work.get_id() }}
			},function(r){
				$('.query[query_id = {{ request.GET('id') }}] .query-works .works li[work={{ work.get_id() }}]').remove();
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
		Удалить из заявки работу "{{ work.get_name() }}"?
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary remove_work">Удалить</div>
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>
</div>
{% endblock html %}