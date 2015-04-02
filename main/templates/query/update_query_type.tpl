{% extends "ajax.tpl" %}

{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-general-query_type').html(get_hidden_content());
{% endblock %}

{% block html %}
  {{ query.get_query_type().get_name() }}
{% endblock %}