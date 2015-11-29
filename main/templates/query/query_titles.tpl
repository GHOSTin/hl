{% for query in queries %}
  <li class="query_status_{{query.get_status()}} query get_query_content row" query_id="{{ query.get_id() }}">
    {% include 'query/build_query_title.tpl' %}
  </li>
{% else %}
  Нет заявок
{% endfor %}