{% extends "ajax.tpl" %}
{% block js %}
	show_dialog(get_hidden_content());
	{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Форма создания заявки</h3>
    </div>	
	<div class="modal-body">
		<a href="/query/get_print_form?query_id={{component.query_id}}">Напечатать наряд-заявку</a>
	</div>
	<div class="modal-footer">
		<div class="btn close_dialog">Отмена</div>
	</div>	  
</div>
{% endblock html %}