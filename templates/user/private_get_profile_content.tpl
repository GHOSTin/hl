{% extends "ajax.tpl" %}
{% set user = response.user %}
{% set company = response.company %}
{% set profile = response.profile %}
{% set profile_name = response.profile_name %}
{% block js %}
    $('.user[user = {{ user.get_id() }}] .company[company = {{ company.get_id() }}] .profile[profile = "{{ profile }}"]').append(get_hidden_content());
{% endblock js %}
{% block html %}
    <ul class="profile-content list-unstyled">
        <li class="profile-content-menu">
            <a class="btn btn-link get_dialog_delete_profile">Удалить профиль</a>
        </li>
        <li class="profile-content-content">{% include '@user/build_profile.tpl' %}</li>
    </ul>
{% endblock html %}