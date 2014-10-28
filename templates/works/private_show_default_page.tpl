{% extends "default.tpl" %}

{% block component %}
    <div class="row">
        <div class="col-md-6">
            <h3>Группы работ</h3>
            <ul class="list-unstyled">
            {% for group in response.groups %}
                <li class="workgroup" workgroup_id="{{ group.get_id() }}">
                    <div class="workgroup-title">{{ group.get_name() }}</div>
                </li>
            {% endfor %}
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