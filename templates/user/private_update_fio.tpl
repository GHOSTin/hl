{% extends "ajax.tpl" %}
{% set user = component.user %}
{% block js %}
    $('.user[user = {{ user.get_id() }}] .user-information').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% include '@user/build_user_content.tpl' %}
{% endblock html %}