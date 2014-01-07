{% extends "ajax.tpl" %}
{% set query = component.query %}
{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-comments').append(get_hidden_content())
{% endblock js %}
{% block html %}
	<ul class="query-sub">

	</ul>
{% endblock html %}