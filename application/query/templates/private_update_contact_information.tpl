{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
	$('.query[query_id = {{query.id}}] .query-general-contacts').html(get_hidden_content());
{% endblock js %}
{% block html %}
	<li>ФИО: {{query.contact_fio}}</li>
	<li>Телефон: {{query.contact_telephone}}</li>
	<li>Сотовый: {{query.contact_cellphone}}</li>
{% endblock html %}