{% extends "ajax.tpl" %}
{% set query = component.query %}
{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-general-reason').html(get_hidden_content());
{% endblock js %}
{% block html %}
  {{ query.get_close_reason() }}
{% endblock html %}