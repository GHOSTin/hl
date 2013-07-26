{% extends "ajax.tpl" %}
{% set groups = component.groups %}
{% block js %}
    $('.letter-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <div>
        <a class="get_dialog_create_group">Создать группу</a>
    </div>
    <ul class="unstyled">
        {% include '@user/build_groups.tpl' %}
    </ul>
{% endblock html %}