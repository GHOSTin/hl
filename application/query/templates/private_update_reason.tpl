{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
	$('.query[query_id = {{query.id}}] .query-general-reason').html(get_hidden_content());
{% endblock js %}
{% block html %}
{{query.close_reason}}
{% endblock html %}