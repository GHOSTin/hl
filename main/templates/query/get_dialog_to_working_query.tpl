{% extends "ajax.tpl" %}

{% block js %}
	show_dialog(get_hidden_content());
	$('.to_working_query').click(function(){
		$.get('to_working_query',{
			id: {{ query.get_id() }}
		},function(r){
			init_content(r);
			$('.dialog').modal('hide');
		});
	});
{% endblock js %}

{% block html %}
<div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <h3>Передача заявки в работу.</h3>
      </div>
    <div class="modal-body">
      Передать заявку в работу?
    </div>
    <div class="modal-footer">
      <div class="btn btn-primary to_working_query">Передать</div>
      <div class="btn btn-default close_dialog">Отмена</div>
    </div>
  </div>
</div>
{% endblock html %}