{% extends "ajax.tpl" %}

{% block js %}
	show_dialog(get_hidden_content());
	$('.get_dialog_initiator').click(function(){
		$.get('get_dialog_initiator',{
			value: $(this).attr('param')
		},function(r){
			init_content(r);
		});
	});
{% endblock %}

{% block html %}
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Форма создания заявки</h3>
    </div>
    <div class="modal-body">
      Выберите: <a class="get_dialog_initiator" param="number">заявка на лицевой счет</a> или <a class="get_dialog_initiator" param="house">заявка на дом</a>
    </div>
    <div class="modal-footer">
      <div class="btn btn-default close_dialog">Отмена</div>
    </div>
  </div>
</div>
{% endblock %}