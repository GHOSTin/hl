{% extends "ajax.tpl" %}
{% set user = component.user %}
{% set company = component.company %}
{% set profile = component.profile %}
{% set profile_name = component.profile_name %}
{% block js %}
    $('.user[user = {{ user.id }}] .company[company = {{ company.id }}] .profile[profile = "{{ profile_name }}"]').append(get_hidden_content());
{% endblock js %}
{% block html %}
    <ul class="profile-content unstyled">
        <li class="profile-content-menu">
            <a class="get_dialog_delete_profile">Удалить профиль</a>
        </li>
        <li class="profile-content-content">{% include '@user/build_profile.tpl' %}</li>
    </ul>
{% endblock html %}