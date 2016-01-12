{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <!-- scroller -->
    <div class="scroll-to-top">
      <i class="fa fa-angle-up"></i>
    </div>
  <!-- /scroller -->
  <!-- left block -->
  <div class="col-sm-4 col-md-4 col-lg-3">
    <div class="ibox float-e-margins">
      <div class="ibox-content mailbox-content">
        <div class="file-manager">
          <div class="btn-group query_controls">
            {% if user.check_access('queries/create_query') %}
            <a class="get_dialog_create_query btn btn-white compose-mail">Создать заявку</a>
            {% endif %}
          </div>
          <div class="hidden-xs">
            <div  class="page-header">
              <h4 class="view-toggle-filters">Фильтры / <a class="selections">Выборки</a> / <a class="get_search compose-mail"><i class="fa fa-search"></i></a>
              </h4><a class="cm clear_filters absolute_hide">сбросить</a>
            </div>
            {% include 'query/filters.tpl' %}
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /left block-->
  <!-- right block -->
  <div class="col-sm-8 col-md-8 col-lg-9 query-container">
    <!-- begin timeline -->
    <nav class="row">
      <div class="calendar col-md-6 col-lg-4 col-xs-12 p-w-xs">
        <div class="ibox-content m-t m-b">
          <div id="queries-datetimepicker"></div>
          <input value="{{ timeline }}" class="hidden default-date">
        </div>
      </div>
      <div class="day_stats col-lg-8 col-md-6 col-xs-12"></div>
    </nav>
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
  <script src="/js/jquery.ui.widget.js"></script>
  <script src="/js/jquery.iframe-transport.js"></script>
  <script src="/js/jquery.fileupload.js"></script>
  <script src="/js/chart.min.js"></script>
{% endblock javascript %}

{% block css %}
  <link rel="stylesheet" href="/css/query.css" >
  <link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css" >
  <link rel="stylesheet" href="/css/plugins/iCheck/custom.css" >
  <!-- Sweet Alert -->
  <link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
{% endblock css %}