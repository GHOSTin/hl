{% extends "ajax.tpl" %}
{% set query = response.query %}
{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-general-contacts').html(get_hidden_content());
{% endblock js %}
{% block html %}
	<li>ФИО: {{ query.get_contact_fio() }}</li>
	<li>Телефон: {{ query.get_contact_telephone() }}</li>
	<li>Сотовый: {{ query.get_contact_cellphone() }}</li>
{% endblock html %}