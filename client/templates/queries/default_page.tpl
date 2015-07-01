{% extends "private.tpl" %}

{% block content %}
<div class="content">
  <div class="row">
    <div class="col-md-12">
    {% if count > 0 %}
      <p class="alert alert-warning">Можно создать только один запрос в 24 часа.</p>
    {% else %}
      <p>
        <a href="/queries/request/">Создать запрос</a>
      </p>
    {% endif %}
    {% include 'queries/requests.tpl' %}
    </div>
  </div>
  <div class="row">
  {% include 'queries/build_queries_titles.tpl' %}
  </div>
</div>
{% endblock %}

{% block js %}
<script src="/js/queries.js"></script>
{% endblock js %}