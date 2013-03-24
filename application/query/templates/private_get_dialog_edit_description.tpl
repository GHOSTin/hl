{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
	show_dialog(get_hidden_content());
	$('.update_description').click(function(){
		$.get('update_description',{
			id: {{query.id}},
			description: $('.dialog-description').val()
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
		<textarea class="dialog-description" style="width:500px; height:100px;">{{query.description}}</textarea>
	</div>
	<div class="modal-footer">
		<div class="btn update_description">Сохранить</div>
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}