{% extends "ajax.tpl" %}
{% set user = response.user %}
{% block js %}
    $('.user[user = {{ user.get_id() }}] .user-information').html(get_hidden_content());
{% endblock js %}
{% block html %}
    <div>
        <a class="btn btn-link get_dialog_add_profile">Добавить профиль</a>
    </div>
    <ul class="company-content list-unstyled">
        <li class="company-content-content">
            <ul class="list-unstyled">
                {% include '@user/build_profiles.tpl' %}
            </ul>
        </li>
    </ul>
{% endblock html %}