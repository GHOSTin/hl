{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
	show_dialog(get_hidden_content());
	{% if query.status == 'open' %}
		$('.print_query').click(function(){
			$('.dialog').modal('hide');
			$.get('to_working_query',{
				id: {{query.id}}
				},function(r){
					init_content(r);
				});
		});
	{% endif %}
	{% endblock js %} 
{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>Документы</h3>
    </div>	
	<div class="modal-body">
		<a href="/query/print_query?id={{query.id}}" target="_blank" class="
		{% if query.status == 'open' %}
			print_query
		{% else %}
			close_dialog
		{% endif %}
		">Напечатать наряд-заявку</a>
	</div>
	<div class="modal-footer">
		<div class="btn btn-default close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}