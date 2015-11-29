{% extends "ajax.tpl" %}

{% block js %}
	$('.query[query_id = {{ query.get_id() }}]').find('.query-users .ibox-content').append(get_hidden_content())
{% endblock js %}

{% block html %}
  {% include 'query/query_users.tpl' %}
{% endblock html %}