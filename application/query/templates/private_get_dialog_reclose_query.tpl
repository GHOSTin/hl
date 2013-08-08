{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.reclose_query').click(function(){
		$.get('reclose_query',{
			id: {{query.id}}
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock js %}
{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>Перезакрытие заявки</h3>
    </div>	
	<div class="modal-body">
		Перезакрыть заявку?
	</div>
	<div class="modal-footer">
		<div class="btn btn-primary reclose_query">Перезакрыть</div>
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}