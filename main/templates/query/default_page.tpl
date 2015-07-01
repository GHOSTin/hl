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
      <h4 class="view-toggle-filters">Фильтры
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
  <div class="row">{% include 'query/number_requests.tpl' %}</div>
  <!-- queries -->
  <div class="queries row">{% include 'query/query_titles.tpl' %}</div>
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
{% endblock javascript %}

{% block css %}
  <link rel="stylesheet" href="/css/query.css" >
  <link rel="stylesheet" href="/css/datepicker.css" >
{% endblock css %}