{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-6">
    <div class="ibox">
      <div class="ibox-title">
        <h5>Группы работ</h5>
        <div class="ibox-tools">
          <a class="btn btn-xs btn-primary get_dialog_create_workgroup"><i class="fa fa-plus"></i></a>
        </div>
      </div>
      <div class="ibox-content">
        <ul class="workgroups list-group clear-list m-b-n-sm">
          {% include 'works/workgroups.tpl' %}
        </ul>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="ibox">
      <div class="ibox-title">
        <h5>Работы</h5>
        <div class="ibox-tools">
          <a class="btn btn-xs btn-primary get_dialog_create_work"><i class="fa fa-plus"></i></a>
        </div>
      </div>
      <div class="ibox-content">
        <ul class="works list-group clear-list m-b-n-sm">
          {% include 'works/works.tpl' %}
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="ibox">
      <div class="ibox-title">
        <h5>События</h5>
        <div class="ibox-tools">
          <a class="btn btn-xs btn-primary get_dialog_create_event"><i class="fa fa-plus"></i></a>
        </div>
      </div>
      <div class="ibox-content">
        <ul class="events list-group clear-list m-b-n-sm">
          {% include 'works/events.tpl' %}
        </ul>
      </div>
    </div>
  </div>
</div>
{% endblock component %}

{% block javascript %}
<script src="/js/works.js"></script>
{% endblock %}

{% block css %}
<link rel="stylesheet" href="/css/works.css" >
{% endblock %}