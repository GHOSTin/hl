{% extends "default.tpl" %}
{% set metrics = response.metrics %}
{% block component %}
  <div class="row">
    <div class="col-md-2">
      <p><a href="/metrics/" class="btn btn-default">Перейти к показаниям</a></p>
    </div>
    <div class="col-md-10">
      <div class="row">
        <div class="col-xs-2">
          <input class="form-control get_date_metrics" placeholder="Выберите дату" readonly>
        </div>
      </div>
      <form id="metrics">
        <p>Для вывода показаний выберите дату.</p>
      </form>
    </div>
  </div>
{% endblock%}
{% block javascript %}
  <script src="/js/metrics.js"></script>
  <script src="/js/bootstrap-datepicker.js"></script>
{% endblock%}
{% block css %}
  <link rel="stylesheet" href="/css/datepicker.css" >
{% endblock css %}