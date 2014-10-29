{% extends "default.tpl" %}

{% set workgroups = response.groups %}

{% block component %}
    <div class="row">
        <div class="col-md-6">
            <h3>Группы работ</h3>
            <ul class="nav nav-pills">
                <li><a href="#" class="get_dialog_create_workgroup">Создать</a></li>
            </ul>
            <ul class="workgroups">
            {% include 'works/workgroups.tpl' %}
            </ul>
        </div>
        <div class="col-md-6">
            <h3>Работы</h3>
            <ul class="list-unstyled">
            {% for work in response.works %}
                <li>{{ work.get_name() }}</li>
            {% endfor %}
            </ul>
        </div>
    </div>
{% endblock component %}

{% block javascript %}
    <script src="/js/works.js"></script>
{% endblock %}

{% block css %}
    <link rel="stylesheet" href="/css/works.css" >
{% endblock %}