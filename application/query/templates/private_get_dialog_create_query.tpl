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
{% endblock js %}
{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>Форма создания заявки</h3>
    </div>	
	<div class="modal-body">
		Выберите: <span class="cm get_dialog_initiator" param="number">заявка на лицевой счет</span> или <span class="cm get_dialog_initiator" param="house">заявка на дом</span>
	</div>
	<div class="modal-footer">
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}