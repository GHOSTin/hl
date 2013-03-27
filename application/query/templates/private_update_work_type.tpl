{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
	$('.query[query_id = {{query.id}}] .query-general-work_type').html(get_hidden_content());
{% endblock js %}
{% block html %}
	{{query.work_type_name}}
{% endblock html %}