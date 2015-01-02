{% extends "public.tpl" %}

{% block content %}
<div id="content" class="col-md-5 col-sm-6 col-lg-4">
  <div class="row">
    <div class="col-md-12">
      Запрашиваемого лицевого счета №{{ number }} не существует. <a href="/recovery/">Попробуйте снова</a>.
    </div>
  </div>
</div>
{% endblock %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock %}