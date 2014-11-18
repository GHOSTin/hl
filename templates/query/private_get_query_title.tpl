{% extends "ajax.tpl" %}
{% set query = response.query %}
{% block js %}
	$('.query[query_id={{ query.get_id() }}]').html(get_hidden_content()).addClass('get_query_content');
{% endblock js %}
{% block html %}
	{% include '@query/build_query_title.tpl' %}
{% endblock html %}
