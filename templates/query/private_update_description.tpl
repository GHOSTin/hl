{% extends "ajax.tpl" %}
{% set query = response.query %}
{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-general-description').html(get_hidden_content());
{% endblock js %}
{% block html %}
  {{ query.get_description() }}
{% endblock html %}