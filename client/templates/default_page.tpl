{% extends "private.tpl" %}

{% block content %}
<div class="content row">
  <div class="col-md-5">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5>Общая информация</h5>
      </div>
      <div class="ibox-content">
        <div class="row">
          <div class="col-lg-12">
            <h2 class="no-margins">{{ number.get_fio() }}</h2>
            <h3>{{ number.get_flat().get_house().get_street().get_name() }}, дом {{ number.get_flat().get_house().get_number() }}, кв. {{ number.get_flat().get_number() }}</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <span class="label label-warning pull-right">сегодня</span>
        <h5>Задолженость</h5>
      </div>
      <div class="ibox-content">
        <div class="row">
          <div class="col-lg-12">
            <h1 class="no-margins">{{ number.get_debt()|number_format(2, '.', ' ') }} руб.</h1>
          </div>
        </div>
      </div>
    </div>
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