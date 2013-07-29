{% extends "ajax.tpl" %}
{% set user = component.users[0] %}
{% block js %}
    $('.user[user = {{user.id}}] .user-information').html(get_hidden_content());
{% endblock js %}
{% block html %}
    {% include '@user/build_user_content.tpl' %}
{% endblock html %}