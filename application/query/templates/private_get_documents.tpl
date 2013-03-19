{% extends "ajax.tpl" %}
{% block js %}
	show_dialog(get_hidden_content());
	{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Документы</h3>
    </div>	
	<div class="modal-body">
		<a href="/query/print_query?id={{component.query_id}}" target="_blank">Напечатать наряд-заявку</a>
	</div>
	<div class="modal-footer">
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}