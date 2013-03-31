{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{% set query = component.queries[0] %}
	{% block js %}
		$('.query[query_id = {{query.id}}] .query-works .works').html(get_hidden_content())
	{% endblock js %}
	{% block html %}
		{% include '@query/query_works.tpl'%}
	{% endblock html %}
{% endif %}