{% extends "ajax.tpl" %}

{% set query = file.get_query() %}

{% block js %}
	show_dialog(get_hidden_content());
	$('.delete_file').click(function(){
		$.get('/queries/{{ query.get_id() }}/files/{{ file.get_path() }}/delete/',
      function(r){
        $('.query[query_id = {{ query.get_id() }}] .files').html(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock %}

{% block html %}
<div class="modal-content">
    <div class="modal-header">
      <h3>Удаление файла</h3>
    </div>
	<div class="modal-body">
		Удалить файл <strong>{{ file.get_name() }}</strong>?
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary delete_file">Удалить</div>
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>
</div>
{% endblock %}