{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-2">
    <ul class="nav nav-pills menu">
      <li>
        <a href="/system/">Вернуться</a>
      </li>
    </ul>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-pills">
      <li>
        <a class="get_dialog_create_query_type">Создать</a>
      </li>
    </ul>
    <ul class="query_types">{% include 'system/query_types/query_types.tpl' %}</ul>
  </div>
</div>
{% endblock %}

{% block javascript %}
<script src="/js/query_type.js"></script>
{% endblock %}