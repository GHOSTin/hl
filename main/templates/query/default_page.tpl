{% extends "default.tpl" %}

{% block component %}
<!-- left block -->
<div class="col-sm-3 col-lg-3">
  <div class="row">
    <div class="btn-group col-xs-12 query_controls">
      {% if user.check_access('queries/create_query') %}
      <div class="get_dialog_create_query btn btn-default col-xs-8">Создать заявку</div>
      {% endif %}
      <div class="get_search btn btn-default col-xs-4">Поиск</div>
    </div>
    <div  class="page-header col-xs-12 col-lg-10 col-lg-push-1">
      <h4 class="view-toggle-filters">Фильтры / <a class="selections">Выборки</a>
      </h4><a class="pull-right cm clear_filters absolute_hide">сбросить</a>
    </div>
    {% include 'query/filters.tpl' %}
  </div>
</div>
<!-- /left block-->
<!-- right block -->
<div class="col-sm-9 col-lg-9">
  <!-- begin timeline -->
  <nav class="timeline row">{% include 'query/timeline.tpl' %}</nav>
  <!-- /timeline -->
  <!-- requests -->
  <div class="row">
    <div class="col-md-12 requests"></div>
  </div>
  <!-- /requests -->
  <div class="row">
    <div class="day_stats col-md-5"></div>
  </div>
  <!-- queries -->
  <div class="row">
    <div class="queries col-md-12">{% include 'query/query_titles.tpl' %}</div>
  </div>
  <!-- /queries -->
</div>
<!-- /right block -->
{% endblock component %}

{% block javascript %}
  <script src="/js/query.js"></script>
  <script src="/js/bootstrap-datepicker.js"></script>
  <script src="/js/jquery.ui.widget.js"></script>
  <script src="/js/jquery.iframe-transport.js"></script>
  <script src="/js/jquery.fileupload.js"></script>
  <script src="/js/chart.min.js"></script>
  <script src="/js/underscore.js"></script>
  <script src="/js/inputmask.js" type="text/javascript"></script>
  <script src="/js/jquery.inputmask.js" type="text/javascript"></script>
  <script id="stats_template" type="text/template">
    <h4 class="text-center">Дневная статистика</h4>
    <div class="row">
      <div class="col-md-5">
        <canvas id="chart" width="120px" height="120px"></canvas>
      </div>
      <div class="col-md-7">
        <ul class="list-unstyled">
          <li>Открытых заявок: <%= open %></li>
          <li>Заявок в работе: <%= working %></li>
          <li>Закрытых заявок: <%= close %></li>
          <li>Переоткрытых заявок: <%= reopen %></li>
          <li>Всех заявок: <%= sum %></li>
        </ul>
      </div>
    </div>
  </script>
{% endblock javascript %}

{% block css %}
  <link rel="stylesheet" href="/css/query.css" >
  <link rel="stylesheet" href="/css/datepicker.css" >
{% endblock css %}