{% if queries != false %}
  {% set open = 0 %}
  {% set close = 0 %}
  {% set working = 0 %}
  {% set reopen = 0 %}
  {% for query in queries %}
    {% if query.get_status() == 'open' %}
      {% set open = open + 1 %}
    {% endif %}
    {% if query.get_status() == 'working' %}
      {% set working = working + 1 %}
    {% endif %}
    {% if query.get_status() == 'close' %}
      {% set close = close + 1 %}
    {% endif %}
    {% if query.get_status() == 'reopen' %}
      {% set reopen = reopen + 1 %}
    {% endif %}
  {% endfor %}
    <div class="muted">
      <small>
        {{ open }} открытых + {{ working }} в работе + {{ close }} закрытых + {{ reopen }} переоткрытых  = {{ queries|length }} заявок
      </small>
    </div>
  {% for query in queries %}
    <div class="row">
      <div class="query get_query_content col-md-12" query_id="{{ query.get_id() }}">
      {% include 'query/build_query_title.tpl' %}
      </div>
    </div>
  {% endfor %}
{% else %}
  Нет заявок
{% endif %}