{% extends "ajax.tpl" %}

{% block js %}
	show_dialog(get_hidden_content());
	$('.update_description').click(function(){
		$.get('update_description',{
			id: {{ query.get_id() }},
			description: $('.dialog-description').val()
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
          <h3>Описания заявки</h3>
      </div>
    <div class="modal-body">
      <textarea class="dialog-description form-control" rows="5">{{ query.get_description() }}</textarea>
    </div>
    <div class="modal-footer">
      <div class="btn btn-primary update_description">Сохранить</div>
      <div class="btn btn-default close_dialog">Отмена</div>
    </div>
  </div>
</div>
{% endblock html %}