{% extends "private.tpl" %}

{% block content %}
{% include 'queries/requests.tpl' %}
<div class="ibox">
  <div class="ibox-title">
    <h5>Все заявки</h5>
    <div class="ibox-tools">
      {% if count > 0 %}
        <button type="button" class="btn btn-primary btn-xs" id="showsimple">Создать запрос</button>
      {% else %}
        <a href="/queries/request/" class="btn btn-primary btn-xs">Создать запрос</a>
      {% endif %}
    </div>
  </div>
  <div class="ibox-content">
    <input type="text" placeholder="Поиск" class="input-sm form-control" id="filter">
    <div class="project-list">
      <table class="table table-hover footable toggle-arrow-tiny" data-filter=#filter>
        <thead>
          <tr>
            <th data-toggle="true">Статус</th>
            <th>Заявка</th>
            <th data-hide="all">Запрос</th>
            <th>Описание</th>
            <th data-hide="all">Диспетчер</th>
            <th data-hide="all">Причина закрытия</th>
          </tr>
        </thead>
        <tbody>
          {% include 'queries/build_queries_titles.tpl' %}
        </tbody>
      </table>
    </div>
  </div>
</div>
{% endblock %}

{% block js %}
<script src="/js/queries.js"></script>
{% endblock js %}

{% block css %}
  <link href="/css/plugins/toastr/toastr.min.css" rel="stylesheet">
  <link href="/css/plugins/footable/footable.core.css" rel="stylesheet">
{% endblock css %}