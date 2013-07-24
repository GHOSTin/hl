{% extends "ajax.tpl" %}
{% set users = component.users %}
{% block js %}
    $('.letter-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <div>
        <a class="get_dialog_create_user">Создать нового пользователя</a>
    </div>
    <ul class="unstyled">
        {% include '@user/build_users.tpl' %}
    </ul>
{% endblock html %}