{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-12">
    <ol class="breadcrumb">
      <li><a href="/system/">Система</a></li>
      <li class="active">Регистриции в личный кабинет</li>
    </ol>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <ul class="requests list-unstyled"></ul>
  </div>
</div>
{% endblock %}

{% block javascript %}
    <script src="/js/registrations.js"></script>
{% endblock %}