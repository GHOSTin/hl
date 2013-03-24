{% extends "ajax.tpl" %}
{% set query = component.queries[0] %}
{% block js %}
	$('.query[query_id = {{query.id}}] .query-general-description').html(get_hidden_content());
{% endblock js %}
{% block html %}
{{query.description}}
{% endblock html %}