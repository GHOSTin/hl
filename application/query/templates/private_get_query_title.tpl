{% extends "ajax.tpl" %}
{% set query = component.query %}
{% if component.queries != false %}
	{% block js %}
		$('.query[query_id={{query.id}}]').html(get_hidden_content()).addClass('get_query_content');
	{% endblock js %}
	{% block html %}
		{% include '@query/build_query_title.tpl' %}
	{% endblock html %}
{% endif %}