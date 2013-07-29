{% extends "ajax.tpl" %}
{% set user = component.user %}
{% set company = component.company %}
{% set profile = component.profile %}
{% set profile_name = component.profile_name %}
{% block js %}
    $('.user[user = {{ user.id }}] .company[company = {{ company.id }}] .profile[profile = "{{ profile_name }}"]').append(get_hidden_content());
{% endblock js %}
{% block html %}
    <div class="profile-content unstyled">
        {% include '@user/build_profile.tpl' %}
    </div>
{% endblock html %}