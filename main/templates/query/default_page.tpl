{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <!-- scroller -->
    <div class="scroll-to-top">
      <i class="fa fa-angle-up"></i>
    </div>
  <!-- /scroller -->
  <!-- left block -->
  <div class="col-sm-3 col-lg-3">
    <div class="ibox float-e-margins">
      <div class="ibox-content mailbox-content">
        <div class="file-manager">
          <div class="btn-group query_controls">
            {% if user.check_access('queries/create_query') %}
            <a class="get_dialog_create_query btn btn-white compose-mail">Создать заявку</a>
            {% endif %}
            <a class="get_search btn btn-white compose-mail"><i class="fa fa-search"></i></a>
          </div>
          <div  class="page-header">
            <h4 class="view-toggle-filters">Фильтры / <a class="selections">Выборки</a>
            </h4><a class="cm clear_filters absolute_hide">сбросить</a>
          </div>
          {% include 'query/filters.tpl' %}
        </div>
      </div>
    </div>
  </div>
  <!-- /left block-->
  <!-- right block -->
  <div class="col-sm-9 col-lg-9 query-container">
    <!-- begin timeline -->
    <nav class="timeline row">{% include 'query/timeline.tpl' %}</nav>
    <!-- /timeline -->
    <!-- requests -->
    <!-- outages -->
    <div class="row m-t m-b">
      <span class="requests"></span>
      <span class="outages"></span>
      <span class="all_noclose"></span>
    </div>
    <!-- /requests -->
    <!-- /outages -->
    <div class="row">
      <div class="day_stats col-md-5 col-xs-12"></div>
    </div>
    <!-- queries -->
    <div class="row">
      <ul class="queries connectList agile-list no-padding">{% include 'query/query_titles.tpl' %}</ul>
    </div>
    <!-- /queries -->
  </div>
  <!-- /right block -->
</div>
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
  <script id="blank" type="text/template">
    <div class="row">
      <div class="col-md-12 text-center">
        <i class="fa fa-spinner fa-pulse"></i> загрузка
      </div>
    </div>
  </script>

  <script id="noclose_stats" type="text/template">
    <h4 class="text-center">Статистика по выборке</h4>
    <div class="row">
      <div class="col-md-5">
        <canvas id="chart" width="120px" height="120px"></canvas>
      </div>
      <div class="col-md-7">
        <ul class="list-unstyled">
          <li>Открытых заявок: <%= open %></li>
          <li>Заявок в работе: <%= working %></li>
          <li>Переоткрытых заявок: <%= reopen %></li>
          <li>Итого заявок: <%= sum %></li>
        </ul>
      </div>
    </div>
  </script>
  <script id="blank" type="text/template">
    <div class="row">
      <div class="col-md-12 text-center">
        <i class="fa fa-spinner fa-pulse"></i> загрузка
      </div>
    </div>
  </script>
{% endblock javascript %}

{% block css %}
  <link rel="stylesheet" href="/css/query.css" >
  <link rel="stylesheet" href="/css/datepicker.css" >
  <link rel="stylesheet" href="/css/plugins/iCheck/custom.css" >
{% endblock css %}