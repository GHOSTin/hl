{% extends "ajax.tpl" %}
{% set query = component.query %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.close_query').click(function(){
		$.get('close_query',{
			id: {{ request.GET('id') }},
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
        <h3>Описания заявки</h3>
    </div>	
	<div class="modal-body">
		<textarea class="dialog-reason" style="width:500px; height:100px;"></textarea>
	</div>
	<div class="modal-footer">
		<div class="btn close_query">Сохранить</div>
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}