{% for query_type in query_types %}
<li class="query_type" query_type_id="{{ query_type.get_id() }}">
  <div class="query_type-title">{{ query_type.get_name() }}</div>
</li>
{% endfor %}