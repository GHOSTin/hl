{% extends "default.tpl" %}

{% block component %}
<!-- scroller -->
<div class="scroll-to-top">
  <i class="fa fa-angle-up"></i>
</div>
<!-- /scroller -->
<ul class="nav nav-pills">
  <li role="presentation" class="active get_streets"><a href="#">Улицы</a></li>
  <li role="presentation" class="get_events"><a href="#">События</a></li>
  <li role="presentation" class="get_outages"><a href="#">Отключения</a></li>
</ul>
<div class="row">
  <div class="col-md-12 workspace-path"><ol class="breadcrumb"></ol></div>
</div>
<div class="row">
  <div class="col-md-12 workspace">{% include 'number/build_street_titles.tpl' %}</div>
</div>
{% endblock component %}

{% block javascript %}
<script src="/js/typeahead.min.js"></script>
<script src="/js/inputmask.js" type="text/javascript"></script>
<script src="/js/jquery.inputmask.js" type="text/javascript"></script>
<!-- DROPZONE -->
{#<script src="/js/plugins/dropzone/dropzone.js"></script>#}
  <div id="preview-template" hidden="hidden">
    <div class="dz-preview dz-file-preview">
      <div class="dz-details">
        <div class="dz-filename"><span data-dz-name></span></div>
        <div class="dz-size" data-dz-size></div>
        <img data-dz-thumbnail />
      </div>
      <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
      <div class="dz-success-mark"><span>✔</span></div>
      <div class="dz-error-mark"><span>✘</span></div>
      <div class="dz-error-message"><span data-dz-errormessage></span></div>
    </div>
  </div>
<script src="/js/number.js"></script>
{% endblock javascript %}

{% block css %}
<link rel="stylesheet" href="/css/number.css" >
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.min.css" >
<link href="/css/plugins/dropzone/basic.css" rel="stylesheet">
<link href="/css/plugins/dropzone/dropzone.css" rel="stylesheet">
<!-- Sweet Alert -->
<link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
{% endblock css %}