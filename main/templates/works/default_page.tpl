{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-6">
    <h3>Группы работ</h3>
    <ul class="nav nav-pills">
      <li>
        <a class="get_dialog_create_workgroup">Создать</a>
      </li>
    </ul>
    <ul class="workgroups">
    {% include 'works/workgroups.tpl' %}
    </ul>
  </div>
  <div class="col-md-6">
    <h3>Работы</h3>
    <ul class="nav nav-pills">
      <li>
        <a class="get_dialog_create_work">Создать</a>
      </li>
    </ul>
    <ul class="works">
    {% include 'works/works.tpl' %}
    </ul>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <h3>События</h3>
    <ul class="nav nav-pills">
      <li>
        <a class="get_dialog_create_event">Создать</a>
      </li>
    </ul>
    <ul class="events">{% include 'works/events.tpl' %}</ul>
  </div>
</div>
{% endblock component %}

{% block javascript %}
<script src="/js/works.js"></script>
{% endblock %}

{% block css %}
<link rel="stylesheet" href="/css/works.css" >
{% endblock %}