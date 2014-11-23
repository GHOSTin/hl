{% extends "ajax.tpl" %}
{% set query = response.query %}
{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-works').append(get_hidden_content())
{% endblock js %}
{% block html %}
	<ul class="query-sub">
		{% if query.get_status() in ['open', 'working', 'reopen'] %}
		<li class="get_dialog_add_work cm">добавить</li>
		{% endif %}
		<li>
			<ol class="works">
			{% include '@query/query_works.tpl' %}
			</ol>
		</li>
	</ul>
{% endblock html %}