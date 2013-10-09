{% extends "ajax.tpl" %}
{% set query = component.query %}
{% block js %}
	show_dialog(get_hidden_content());
	{% if query.get_status() == 'open' %}
		$('.print_query').click(function(){
			$('.dialog').modal('hide');
			$.get('to_working_query',{
				id: {{ query.get_id() }}
				},function(r){
					init_content(r);
				});
		});
	{% endif %}
	{% endblock js %} 
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Документы</h3>
    </div>	
	<div class="modal-body">
		<a href="/query/print_query?id={{ query.get_id() }}" target="_blank" class="
		{% if query.get_status() == 'open' %}
			print_query
		{% else %}
			close_dialog
		{% endif %}
		">Напечатать наряд-заявку</a>
	</div>
	<div class="modal-footer">
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}