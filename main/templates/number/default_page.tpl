{% extends "default.tpl" %}

{% block component %}
<ul class="nav nav-pills">
  <li role="presentation" class="active get_streets"><a href="#">Улицы</a></li>
  <li role="presentation" class="get_outages"><a href="#">Отключения</a></li>
</ul>
<div class="row">
  <div class="col-md-12 workspace-path"></div>
</div>
<div class="row">
  <div class="col-md-12 workspace">{% include 'number/build_street_titles.tpl' %}</div>
</div>
{% endblock component %}

{% block javascript %}
<script src="/js/number.js"></script>
<script src="/js/bootstrap-datepicker.js"></script>
<script src="/js/typeahead.min.js"></script>
<script src="/js/inputmask.js" type="text/javascript"></script>
<script src="/js/jquery.inputmask.js" type="text/javascript"></script>
{% endblock javascript %}

{% block css %}
<link rel="stylesheet" href="/css/number.css" >
<link rel="stylesheet" href="/css/datepicker.css" >
{% endblock css %}