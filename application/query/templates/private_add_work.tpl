{% extends "ajax.tpl" %}
{% set query = component.query %}
{% block js %}
	$('.query[query_id = {{query.id}}] .query-works .works').html(get_hidden_content())
{% endblock js %}
{% block html %}
	{% include '@query/query_works.tpl'%}
{% endblock html %}