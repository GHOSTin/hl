{% extends "ajax.tpl" %}
{% set client_queries = response.client_queries %}
{% block js %}
	$('.client_queries').html(get_hidden_content());
{% endblock js %}
{% block html %}
  {% include '@query/build_client_query_titles.tpl' %}
{% endblock html %}