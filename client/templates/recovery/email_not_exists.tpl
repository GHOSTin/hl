{% extends "public.tpl" %}

{% block content %}
<div class="middle-box text-center animated fadeInDown">
  <div class="row">
    <div class="col-md-12 error-desc">
      К лицевого счету №{{ number }} не привязан email.
      <a href="/" class="m-t btn btn-block btn-outline btn-primary">Вернуться на главную</a>.
    </div>
  </div>
</div>
{% endblock %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock %}