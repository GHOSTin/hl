{% extends "public.tpl" %}

{% block content %}
<div class="middle-box text-center animated fadeInDown">
  <div class="row">
    <div class="col-md-12 error-desc">
      <h3 class="font-bold">Ошибка</h3>
      Запрашиваемого лицевого счета №{{ number }} не существует.
      <a href="/recovery/" class="m-t btn btn-block btn-outline btn-primary">Попробуйте снова</a>.
    </div>
  </div>
</div>
{% endblock %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock %}