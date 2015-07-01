{% extends "private.tpl" %}

{% block content %}
<div class="content row">
  <div class="col-md-6">
  <p>{{ number.get_flat().get_house().get_street().get_name() }}, дом {{ number.get_flat().get_house().get_number() }}, кв. {{ number.get_flat().get_number() }}, {{ number.get_fio() }}</p>
  Задолженость на сегодняшний день <strong>{{ number.get_debt() }} руб.</strong>
  </div>
</div>
{% endblock content %}

{% block js %}
  <script>
    $(document).ready(function(){
      $('#sidebar-nav li').removeClass('active');
      $('#home').addClass('active');
    });
  </script>
{% endblock %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock %}