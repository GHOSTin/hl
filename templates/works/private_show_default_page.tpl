{% extends "default.tpl" %}
{% block component %}
    <div class="row">
        <div class="col-md-6">
            <h3>Группы работ</h3>
            <ul class="list-unstyled">
            {% for group in response.groups %}
                <li>{{ group.get_name() }}</li>
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