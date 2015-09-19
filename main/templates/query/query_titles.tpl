{% for query in queries %}
  <div class="row">
    <div class="query get_query_content col-md-12" query_id="{{ query.get_id() }}">
    {% include 'query/build_query_title.tpl' %}
    </div>
  </div>
{% else %}
  Нет заявок
{% endfor %}