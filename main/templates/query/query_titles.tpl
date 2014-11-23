{% if response.queries != false %}
    <div class="muted">
        <small>
	        Количество заявок: {{ response.queries|length }}
        </small>
    </div>
	{% for query in response.queries %}
		<div class="query get_query_content" query_id="{{ query.get_id() }}">
			{% include '@query/build_query_title.tpl' %}
		</div>
	{% endfor %}
{% else %}
    Нет заявок
{% endif %}