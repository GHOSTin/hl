{% extends "public.tpl" %}

{% block content %}
<div id="content" class="col-md-5 col-sm-6 col-lg-4">
  <div class="row">
    <div class="col-md-12">
      К лицевого счету №{{ number }} не привязан email. <a href="/">Вернуться на главную</a>.
    </div>
  </div>
</div>
{% endblock %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock %}