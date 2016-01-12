{% extends "default.tpl" %}

{% block component %}
  <div class="row">
    <div class="col-md-2">
      <p><a href="/metrics/" class="btn btn-primary"><i class="fa fa-arrow-left fa-fw"></i> К показаниям</a></p>
    </div>
    <div class="col-md-10">
      <div class="row">
        <div class="col-xs-6 col-sm-4 col-lg-2">
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
{% endblock%}

{% block css %}
  <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css" >
{% endblock css %}